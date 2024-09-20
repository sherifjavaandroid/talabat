<?php

namespace App\Traits;

use App\Models\Payment;
use App\Models\SubscriptionVendor;
use App\Models\Wallet;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use PaytmWallet;

trait PayTmTrait
{


    private function getPayTmEnv()
    {
        /* for Production */
        $url = "production";

        /* for Staging */
        if (!\App::environment('production')) {
            $url = "local";
        }
        return $url;
    }
    private function getPayTmWebsiteName()
    {
        /* for Production */
        $name = "DEFAULT";

        /* for Staging */
        if (!\App::environment('production')) {
            $name = "WEBSTAGING";
        }
        return $name;
    }

    private function setPaymentGatewayData($paymentMethod)
    {
        config(['services.paytm-wallet.env' => $this->getPayTmEnv()]);
        config(['services.paytm-wallet.merchant_id' => $paymentMethod->public_key]);
        config(['services.paytm-wallet.merchant_key' => $paymentMethod->secret_key]);
        config(['services.paytm-wallet.merchant_website' => $this->getPayTmWebsiteName()]);
    }

    public function createPayTmPaymentReference($order)
    {

        //
        $paymentMethod = $order->payment_method;
        $this->setPaymentGatewayData($paymentMethod);

        //
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
            'order' => $order->code,
            'user' => $order->user->id,
            'mobile_number' => $order->user->phone,
            'email' => $order->user->email,
            'amount' => $order->payable_total,
            'callback_url' => route('api.payment.callback', ["code" => $order->code, "status" => "success"])
        ]);

        $response = $payment->receive();
        // logger("paytm response", [$response, $response["params"], $response["checkSum"]]);

        //
        $ref = Str::random(14);
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->session_id = $ref;
        $payment->ref = $ref;
        $payment->amount = $order->payable_total;
        $payment->save();
        return $response;
    }

    public function createPayTmTopupReference($walletTransaction, $paymentMethod)
    {

        //
        $this->setPaymentGatewayData($paymentMethod);

        //
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
            'order' => $walletTransaction->ref,
            'user' => $walletTransaction->wallet->user->id,
            'mobile_number' => $walletTransaction->wallet->user->phone,
            'email' => $walletTransaction->wallet->user->email,
            'amount' => $walletTransaction->amount,
            'callback_url' => route('api.wallet.topup.callback', ["code" => $walletTransaction->ref, "status" => "success"])
        ]);

        $response = $payment->receive();

        //
        $ref = Str::random(14);
        $walletTransaction->session_id = $ref;
        $walletTransaction->payment_method_id = $paymentMethod->id;
        $walletTransaction->save();
        return $response;
    }


    public function createPayTmSubscribeReference($subscription, $paymentMethod)
    {

        //
        $this->setPaymentGatewayData($paymentMethod);

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
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
            'order' => $vendorSubscription->code,
            'user' => \Auth::user()->id,
            'mobile_number' => \Auth::user()->phone,
            'email' => \Auth::user()->email,
            'amount' => $subscription->amount,
            'callback_url' => route('api.subscription.callback', ["code" => $vendorSubscription->code, "status" => "success"])
        ]);

        $response = $payment->receive();
        return $response;
    }



    protected function verifyPayTmTransaction($order)
    {

        $paymentMethod = $order->payment_method;
        $this->setPaymentGatewayData($paymentMethod);

        //
        $status = PaytmWallet::with('status');
        $status->prepare(['order' => $order->code]);
        $status->check();
        $response = $status->response();
        //
        if ($status->isSuccessful()) {
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
        } else if ($status->isFailed()) {
            //Transaction Failed
            throw new \Exception("Order is invalid or has already been paid");
        } else if ($status->isOpen()) {
            //Transaction Open/Processing
            throw new \Exception("Order is still being processed");
        }
    }

    protected function verifyPayTmTopupTransaction($walletTransaction)
    {

        $paymentMethod = $walletTransaction->payment_method;
        $this->setPaymentGatewayData($paymentMethod);

        //
        $status = PaytmWallet::with('status');
        $status->prepare(['order' => $walletTransaction->ref]);
        $status->check();
        $response = $status->response();
        //
        if ($status->isSuccessful()) {
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
        } else if ($status->isFailed()) {
            //Transaction Failed
            throw new \Exception("Wallet Topup is invalid or has already been paid");
        } else if ($status->isOpen()) {
            //Transaction Open/Processing
            throw new \Exception("Payment is still being processed");
        }
    }

    protected function verifyPayTmSubscriptionTransaction($vendorSubscription)
    {

        $paymentMethod = $vendorSubscription->payment_method;
        $this->setPaymentGatewayData($paymentMethod);

        //
        $status = PaytmWallet::with('status');
        $status->prepare(['order' => $vendorSubscription->code]);
        $status->check();
        $response = $status->response();
        //
        if ($status->isSuccessful()) {
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
        } else if ($status->isFailed()) {
            //Transaction Failed
            throw new \Exception("Subscription Payment is invalid or has already been paid");
        } else if ($status->isOpen()) {
            //Transaction Open/Processing
            throw new \Exception("Payment is still being processed");
        }
    }
}
