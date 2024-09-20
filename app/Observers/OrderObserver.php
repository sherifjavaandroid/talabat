<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use App\Mail\OrderUpdateMail;
use App\Models\PackageTypePricing;
use App\Services\AppLangService;
use App\Services\JobHandlerService;
use App\Services\MailHandlerService;
use App\Services\OrderEarningService;
use App\Traits\FirebaseAuthTrait;
use App\Traits\FirebaseDBTrait;
use App\Traits\OrderFCMTrait;
use App\Traits\OrderTrait;
use App\Traits\OrderJobTrait;
use App\Traits\DigitalOrderTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{

    use FirebaseDBTrait, FirebaseAuthTrait, FirebaseDBTrait;
    use OrderTrait, OrderFCMTrait, DigitalOrderTrait;
    use OrderJobTrait;

    public function creating(Order $model)
    {
        // logger("Pending Order", [$model]);
        if ((bool) setting('enableNumericOrderCode')) {
            $model->code ??= $this->generateOrderCode(10);
            $model->verification_code = $this->generateOrderCode(5, $check = false);
        } else {
            $model->code ??= Str::random(10);
            $model->verification_code = $this->generateRandomString(5);
        }
        if (empty($model->user_id)) {
            $model->user_id ??= Auth::id();
        }
    }

    public function created(Order $model)
    {
        //sending notifications base on status change of the order
        $this->userIsReady($model);
        $this->sendOrderUpdateMail($model);
        $this->autoMoveToReady($model);
        $this->autoMoveToPreparing($model);
        $this->clearAutoAssignment($model);
        $this->pushOrderToFCM($model);
        $this->handleDigitalOrder($model);
        //Sending regular order to job for driver assignment
        $this->handleRegularAssignmentJob($model);
    }

    public function updating(Order $model)
    {
        AppLangService::tempLocale();
        //payment_status
        if (in_array($model->status, ['delivered', 'completed']) && $model->payment_method != null && $model->payment_method->is_cash) {
            $model->payment_status = "successful";
        }
        AppLangService::restoreLocale();
    }

    public function updated(Order $model)
    {
        AppLangService::tempLocale();
        //sending notifications base on status change of the order
        //driver id changed
        if ($model->isDirty('driver_id')) {
            (new JobHandlerService())->orderFCMNotificationJob($model, 3);
            $this->deleteFirestoreOrderNode($model);
        } elseif (in_array($model->status, ['failed', 'cancelled'])) {
            $this->deleteFirestoreOrderNode($model);
        }
        //Sending regular order to job for driver assignment
        $this->handleRegularAssignmentJob($model);
        //payment request notification
        $this->paymentRequestNotification($model);
        //payment status change notification
        $this->paymentNotification($model);
        //
        $model->refresh();
        $this->sendOrderUpdateMail($model);
        $orderEarningService = new OrderEarningService();
        $orderEarningService->updateEarning($model);

        $model->refundUser();
        $this->updatePaymentStatus($model);
        $this->autoMoveToReady($model);
        $this->autoMoveToPreparing($model);
        $this->clearAutoAssignment($model);
        $this->pushOrderToFCM($model);
        $this->clearFirestore($model);
        $this->handleDigitalOrder($model);

        //revert qty of cancelled order
        $model->refundVendorProducts();
        // $this->resetOrderQty($model);
        AppLangService::restoreLocale();
    }

    //
    public function sendOrderUpdateMail($model)
    {
        //only delivered
        if (in_array($model->status, ['delivered'])) {
            //send mail
            try {
                MailHandlerService::sendMail(
                    new OrderUpdateMail($model),
                    $model->user->email,
                    [$model->vendor->email]
                );
            } catch (\Exception $ex) {
                // logger("Mail Error", [$ex]);
                logger("Mail Error");
            }
        }
    }

    public function autoMoveToReady(Order $order)
    {

        //
        $packageTypePricing = PackageTypePricing::where([
            "vendor_id" => $order->vendor_id,
            "package_type_id" => $order->package_type_id,
        ])->first();

        //
        if (
            in_array($order->status, ["pending", "preparing"])
            && ($packageTypePricing->auto_assignment ?? 0)
            && ($order->payment_method != null && $order->payment_method->is_cash || $order->payment_status == "successful")
        ) {
            //ignore if status is already ready
            if ($order->status != "ready") {
                // logger("Auto move to ready kicked in");
                $order->setStatus("ready");
            }
        }
    }

    public function autoMoveToPreparing(Order $order)
    {

        if (
            in_array($order->status, ["pending"])
            && ($order->vendor->auto_accept ?? 0)
            && ($order->payment_method != null && $order->payment_method->is_cash || $order->payment_status == "successful")
        ) {
            //ignore if status is already preparing
            if ($order->status != "preparing") {
                // logger("Auto move to preparing kicked in");
                $order->setStatus("preparing");
            }
        }
    }

    public function paymentRequestNotification(Order $order)
    {

        if ($order->isDirty('payment_status') && $order->payment_status == "request") {
            (new JobHandlerService())->orderPaymentRequestNotificationJob($order);
        }
    }

    public function paymentNotification(Order $order)
    {

        if ($order->isDirty('payment_status')) {
            (new JobHandlerService())->orderPaymentStatusChangeNotificationJob($order);
        }
    }




    function updatePaymentStatus($order)
    {
        //update payment_status after order is completed by driver or vendor
        $user = User::find(\Auth::id() ?? 0);
        if (
            !empty($user) && $user->hasAnyRole("admin|manager|driver") &&
            in_array($order->status, ["successful", "completed", "delivered"]) &&
            $order->payment_method != null && $order->payment_method->is_cash
        ) {
            $order->payment_status = "successful";
            $order->saveQuietly();
        }

        //calculate earning incase you missed it
        $orderEarningService = new OrderEarningService();
        $orderEarningService->updateEarning($order);
    }

    function userIsReady($model)
    {
        if (empty($model->user->phone)) {
            throw new \Exception(__("User can't place order. Please complete profile setup"), 200203,);
        }
    }
}
