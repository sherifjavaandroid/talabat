<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\AutoAssignment;
use App\Models\PaymentMethod;
use App\Models\Wallet;

trait OrderTrait
{
    use GoogleMapApiTrait;
    use FirebaseAuthTrait;
    use WalletTrait;

    public function getNewOrderStatus(Request $request)
    {

        $orderDate = Carbon::parse("" . $request->pickup_date . " " . $request->pickup_time . "");
        $hoursDiff = Carbon::now()->diffInHours($orderDate);

        if (!empty($request->pickup_date) && $hoursDiff > setting('minScheduledTime', 2)) {
            return "scheduled";
        } else {
            return "pending";
        }
    }


    public function resetOrderQty($model)
    {
        if (!empty($model->products())  && in_array($model->status, ['failed', 'cancelled'])) {
            foreach ($model->products() as $orderProduct) {
                $orderProduct->product->available_qty += $orderProduct->quantity;
                $orderProduct->product->save();
            }
        }
    }


    public function clearAutoAssignment(Order $order)
    {
        //
        $order->refresh();
        if (!empty($order->driver_id) || in_array($order->status, ["ready", "enroute"])) {
            $autoAssignments = AutoAssignment::where('order_id', $order->id)->get();
            if (count($autoAssignments) > 0) {
                AutoAssignment::where('order_id', $order->id)->delete();
            }
        }
    }


    function generateRandomString($length = 25)
    {
        $characters = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generateOrderCode($length = 10, $check = true)
    {
        $characters = '1234567890';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        //
        if ($check) {
            $order = Order::whereCode($randomString)->first();
            if (!empty($order)) {
                $randomString = $this->generateOrderCode($length);
            }
        }
        return $randomString;
    }


    public function orderWalletPaymentProcess($wallet, $total, $order)
    {
        $walletDebit = $total;
        //remove delivery fee from total to allow driver be paid in cash
        if ((bool) setting('finance.delivery.collectDeliveryCash', 0)) {
            $walletDebit = $total - $order->delivery_fee;
        }
        //
        $wallet->balance -= $walletDebit;
        $wallet->save();

        //RECORD WALLET TRANSACTION
        $this->recordWalletDebit($wallet, $walletDebit);
    }

    public function isPayableByWallet($userId = null)
    {
        $userId = $userId ?? request()->user_id ?? auth()->id();
        $paymentMethodId = request()->payment_method_id ?? 0;
        $paymentMethod = PaymentMethod::find($paymentMethodId);
        if (!empty($paymentMethod) && $paymentMethod->is_cash && $paymentMethod->slug == "wallet") {
            $wallet = Wallet::whereUserId($userId)->first();
            if (empty($wallet) || $wallet->balance < request()->total) {
                throw new \Exception(__("Wallet Balance is less than order total amount"), 1);
            }
        }

        return true;
    }

    public function processWalletOrderPayment($request, $order)
    {
        //
        $paymentMethod = PaymentMethod::find($request->payment_method_id ?? 0);
        $message = "";
        $paymentLink = "";

        if (empty($paymentMethod)) {
            $message = __("Order placed successfully. You will be notified once order is prepared and ready");
            if ($order->payment_status == "pending") {
                $paymentLink = route('order.payment', ["code" => $order->code]);
            }
        } else if ($paymentMethod->is_cash) {

            //
            $order->payment_status = "pending";

            //wallet check
            if ($paymentMethod->slug == "wallet") {
                //
                $userId = $request->user_id ?? $order->user_id ?? auth()->id();
                $wallet = Wallet::where('user_id', $userId)->first();
                if (empty($wallet) || $wallet->balance < $request->total) {
                    throw new \Exception(__("Wallet Balance is less than order total amount"), 1);
                } else {
                    //
                    $this->orderWalletPaymentProcess($wallet, $request->total, $order);
                    //mark order payment has successful
                    $order->payment_status = "successful";
                }
            }

            // $order->saveQuietly();
            $message = __("Order placed successfully. Relax while the vendor process your order");
        } else {
            $message = __("Order placed successfully. Please follow the link to complete payment.");
            if ($order->payment_status == "pending") {
                $paymentLink = route('order.payment', ["code" => $order->code]);
            }
        }

        //
        return [
            "message" => $message,
            "link" => $paymentLink
        ];
    }


    //
    public function isOrderAmountPaymentmethodValid($paymentMethodId, $amount)
    {
        try {
            //check if the order total is less than the max order amount for the payment method
            $paymentMethod = PaymentMethod::find($paymentMethodId);
            if ($paymentMethod->max_order != null && $paymentMethod->max_order > 0 && $amount > $paymentMethod->max_order) {
                throw new \Exception(__("Order total is greater than the maximum order amount for the payment method"), 1);
            }
        } catch (\Exception $e) {
            logger("Order Payment Method Validation Error", [
                "paymentMethod" => $paymentMethod,
                "amount" => $amount,
                "paymentMethod" => $paymentMethod,
                "error" => $e,
            ]);
            throw new \Exception($e->getMessage(), 1);
        }
    }
}