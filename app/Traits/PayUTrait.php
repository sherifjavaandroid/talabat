<?php

namespace App\Traits;

use App\Models\Payment;
use App\Models\SubscriptionVendor;
use App\Models\Wallet;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


trait PayUTrait
{



    private function generateHash($paymentMethod, $code, $amount, $productInfo, $firstname, $email)
    {
        $hashSequence = "" . $paymentMethod->secret_key . "|" . $code . "|" . $amount . "|" . $productInfo . "|";
        $hashSequence .= $firstname . "|" . $email . "|||||||||||" . $paymentMethod->hash_key . "";
        $hash = hash("sha512", $hashSequence);
        // logger("New Hash", [$hashSequence, $hash]);
        return [$hashSequence, $hash];
    }

    private function verifyHash($paymentMethod, $code, $amount, $productInfo, $firstname, $email, $repStatus, $hash)
    {
        //
        $hashSequence = "" . $paymentMethod->hash_key . "|" . $repStatus . "|||||||||||" . $email . "|" . $firstname . "|";
        $hashSequence .= $productInfo . "|" . $amount . "|" . $code . "|" . $paymentMethod->secret_key . "";
        //salt|status||||||udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key
        $newHash = hash("sha512", $hashSequence);
        // logger("Data", [$hashSequence, $newHash, $hash, $repStatus]);
        return $newHash == $hash && $repStatus == "success";
    }

    public function createPayUPaymentReference($order)
    {


        //
        $hashArray = $this->generateHash(
            $order->payment_method,
            $order->code,
            $order->payable_total,
            "Order Payment",
            $order->user->name,
            $order->user->email,
        );

        //
        $paymentData = [
            "payU_key" => $order->payment_method->secret_key,
            "payU_hash_string" => $hashArray[0],
            "payU_hash" => $hashArray[1],
            "payU_txnid" => $order->code,
            "payU_amount" => $order->payable_total,
            "payU_firstname" => $order->user->name,
            "payU_email" => $order->user->email,
            "payU_phone" => $order->user->phone,
            "payU_productinfo" => "Order Payment",
            "payU_surl" => route('api.payment.callback', ["code" => $order->code, "status" => "success"]),
            "payU_furl" => route('api.payment.callback', ["code" => $order->code, "status" => "failed"]),
            "payU_service_provider" => "payu_paisa",
        ];


        //
        $ref = Str::random(14);
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->session_id = $ref;
        $payment->ref = $ref;
        $payment->amount = $order->payable_total;
        $payment->save();
        return $paymentData;
    }

    public function createPayUTopupReference($walletTransaction, $paymentMethod)
    {

        //
        $hashArray = $this->generateHash(
            $paymentMethod,
            $walletTransaction->ref,
            $walletTransaction->amount,
            "Wallet Topup Payment",
            $walletTransaction->wallet->user->name,
            $walletTransaction->wallet->user->email,
        );


        //
        $paymentData = [
            "payU_key" => $paymentMethod->secret_key,
            "payU_hash_string" => $hashArray[0],
            "payU_hash" => $hashArray[1],
            "payU_txnid" => $walletTransaction->ref,
            "payU_amount" => $walletTransaction->amount,
            "payU_firstname" => $walletTransaction->wallet->user->name,
            "payU_email" => $walletTransaction->wallet->user->email,
            "payU_phone" => $walletTransaction->wallet->user->phone,
            "payU_productinfo" => "Wallet Topup Payment",
            "payU_surl" => route('api.wallet.topup.callback', ["code" => $walletTransaction->ref, "status" => "success"]),
            "payU_furl" => route('api.wallet.topup.callback', ["code" => $walletTransaction->ref, "status" => "failed"]),
            "payU_service_provider" => "payu_paisa",
        ];
        //
        $ref = Str::random(14);
        $walletTransaction->session_id = $ref;
        $walletTransaction->payment_method_id = $paymentMethod->id;
        $walletTransaction->save();
        return $paymentData;
    }


    public function createPayUSubscribeReference($subscription, $paymentMethod)
    {


        //
        $vendorSubscription = new SubscriptionVendor();
        $vendorSubscription->code = \Str::random(12);
        //payment status
        $vendorSubscription->status = "pending";
        $vendorSubscription->payment_method_id = $paymentMethod->id;
        $vendorSubscription->subscription_id = $subscription->id;
        $vendorSubscription->vendor_id = \Auth::user()->vendor_id;
        $vendorSubscription->save();

        //
        //
        $hashArray = $this->generateHash(
            $paymentMethod,
            $vendorSubscription->code,
            $subscription->amount,
            "Subscription Payment",
            \Auth::user()->name,
            \Auth::user()->email,
        );

        //
        $paymentData = [
            "payU_key" => $paymentMethod->secret_key,
            "payU_hash_string" => $hashArray[0],
            "payU_hash" => $hashArray[1],
            "payU_txnid" => $vendorSubscription->code,
            "payU_amount" => $subscription->amount,
            "payU_firstname" => \Auth::user()->name,
            "payU_email" => \Auth::user()->email,
            "payU_phone" => \Auth::user()->phone,
            "payU_productinfo" => "Subscription Payment",
            "payU_surl" => route('api.payment.callback', ["code" => $vendorSubscription->code, "status" => "success"]),
            "payU_furl" => route('api.payment.callback', ["code" => $vendorSubscription->code, "status" => "failed"]),
            "payU_service_provider" => "payu_paisa",
        ];

        return $paymentData;
    }



    protected function verifyPayUTransaction($order, $hash, $repStatus)
    {

        $isSuccessful = $this->verifyHash(
            $order->payment_method,
            $order->code,
            number_format($order->total, 2, ".", ""),
            "Order Payment",
            $order->user->name,
            $order->user->email,
            $repStatus,
            $hash,
        );
        //
        if ($isSuccessful) {
            //Transaction Successful
            $payment = Payment::where('session_id', $order->payment->session_id)->first();

            //has order been paided for before
            if (empty($order)) {
                throw new \Exception("Order is invalid");
            } else if (!$order->isDirty('payment_status') && $order->payment_status  == "successful") {
                //throw new \Exception("Order is has already been paid");
return;
            }


            try {

                DB::beginTransaction();
                $payment->status = "successful";
                $payment->save();

                $order->payment_status = "successful";
                $order->save();
                DB::commit();
                return;
            } catch (\Exception $ex) {
                throw $ex;
            }
        } else {
            throw new \Exception("Order is invalid or has already been paid");
        }
    }

    protected function verifyPayUTopupTransaction($walletTransaction, $hash, $repStatus)
    {

        $isSuccessful = $this->verifyHash(
            $walletTransaction->payment_method,
            $walletTransaction->ref,
            number_format($walletTransaction->amount, 2, ".", ""),
            "Wallet Topup Payment",
            $walletTransaction->wallet->user->name,
            $walletTransaction->wallet->user->email,
            $repStatus,
            $hash,
        );
        //
        if ($isSuccessful) {
            //Transaction Successful

            //has order been paided for before
            if (empty($walletTransaction)) {
                throw new \Exception("Wallet Topup is invalid");
            } else if (!$walletTransaction->isDirty('status') && $walletTransaction->status == "successful") {
                // throw new \Exception("Wallet Topup is has already been paid");
return;
            }


            try {

                DB::beginTransaction();
                $walletTransaction->status = "successful";
                $walletTransaction->save();

                //
                $wallet = Wallet::find($walletTransaction->wallet->id);
                $wallet->balance += $walletTransaction->amount;
                $wallet->save();
                DB::commit();
                return;
            } catch (\Exception $ex) {
                throw $ex;
            }
        } else {
            //Transaction Failed
            throw new \Exception("Wallet Topup is invalid or has already been paid");
        }
    }

    protected function verifyPayUSubscriptionTransaction($vendorSubscription, $hash, $repStatus)
    {

        $isSuccessful = $this->verifyHash(
            $vendorSubscription->payment_method,
            $vendorSubscription->code,
            number_format($vendorSubscription->subscription->amount, 2, ".", ""),
            "Subscription Payment",
            \Auth::user()->name,
            \Auth::user()->email,
            $repStatus,
            $hash,
        );
        //
        if ($isSuccessful) {
            //Transaction Successful
            //has order been paided for before
            if (empty($vendorSubscription) || $vendorSubscription->status == "successful") {
                throw new \Exception("Subscription Payment is invalid or has already been paid");
            }



            try {

                DB::beginTransaction();
                $vendorSubscription->status = "successful";
                $vendorSubscription->save();
                DB::commit();
                return;
            } catch (\Exception $ex) {
                throw $ex;
            }
        } else {
            //Transaction Failed
            throw new \Exception("Subscription Payment is invalid or has already been paid");
        }
    }
}
