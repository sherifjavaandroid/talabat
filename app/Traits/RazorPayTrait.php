<?php

namespace App\Traits;

use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SubscriptionVendor;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

trait RazorPayTrait
{



    public function createRazorpayPaymentReference($order)
    {

        if ($order->payment == null || $order->payment->status != "pending") {
            $paymentMethod = $order->payment_method;
            $client = new Api($paymentMethod->public_key, $paymentMethod->secret_key);
            $razorPayOrder  = $client->order->create([
                'receipt'         => "" . $order->code . "",
                'amount'          => $order->payable_total * 100,
                'currency'        => "" . $order->getCurrencyCode() . "",
            ]);

            //
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->session_id = $razorPayOrder->id;
            $payment->ref = $razorPayOrder->id;
            $payment->amount = $order->payable_total;
            $payment->save();

            return $razorPayOrder->id;
        } else {
            return $order->payment->session_id;
        }
    }

    public function createRazorpayTopupReference($walletTransaction, $paymentMethod)
    {

        if (empty($walletTransaction->session_id) && $walletTransaction->status == "pending") {

            $client = new Api($paymentMethod->public_key, $paymentMethod->secret_key);
            $razorPayOrder  = $client->order->create([
                'receipt'         => "" . $walletTransaction->ref . "",
                'amount'          => $walletTransaction->amount * 100,
                'currency'        => "" . setting('currencyCode', 'USD') . "",
            ]);

            //
            $walletTransaction->session_id = $razorPayOrder->id;
            $walletTransaction->payment_method_id = $paymentMethod->id;
            $walletTransaction->save();

            return $razorPayOrder->id;
        } else {
            return $walletTransaction->session_id;
        }
    }

    public function createRazorpaySubscribeReference($subscription, $paymentMethod)
    {

        $vendorSubscription = new SubscriptionVendor();
        $vendorSubscription->code = \Str::random(12);
        //payment status
        $vendorSubscription->status = "pending";
        $vendorSubscription->payment_method_id = $paymentMethod->id;
        $vendorSubscription->subscription_id = $subscription->id;
        $vendorSubscription->vendor_id = \Auth::user()->vendor_id;
        $vendorSubscription->save();

        $client = new Api($paymentMethod->public_key, $paymentMethod->secret_key);
        $razorPayOrder  = $client->order->create([
            'receipt'         => "" . $vendorSubscription->code . "",
            'amount'          => $subscription->amount * 100,
            'currency'        => "" . setting('currencyCode', 'USD') . "",
        ]);

        //
        $vendorSubscription->transaction_id = $razorPayOrder->id;
        $vendorSubscription->payment_method_id = $paymentMethod->id;
        $vendorSubscription->save();

        return [$vendorSubscription->code, $razorPayOrder->id];
    }




    protected function verifyRazorpayTransaction($order)
    {

        $paymentMethod = $order->payment_method;
        $client = new Api($paymentMethod->public_key, $paymentMethod->secret_key);
        $razorpayPayment = $client->order->fetch($order->payment->session_id)->payments()->items[0];
        // logger("razorpayPayment", [$razorpayPayment]);
        // logger("razorpayPayment status", [$razorpayPayment->status]);

        if ($razorpayPayment->status == "authorized" || $razorpayPayment->status == "captured") {

            $payment = Payment::where('session_id', $order->payment->session_id)->first();

            //has order been paided for before
            if (empty($order)) {
                throw new \Exception("Order is invalid");
            } else if (!$order->isDirty('payment_status') && $order->payment_status  == "successful") {
                //throw new \Exception("Order is has already been paid");
                return;
            }


            try {

                //capture payment
                if (!$razorpayPayment->captured) {
                    $captureResponse = $razorpayPayment->capture(array('amount' => $order->total * 100));
                }
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

    protected function verifyRazorpayTopupTransaction($walletTransaction)
    {

        $paymentMethod = $walletTransaction->payment_method;
        $client = new Api($paymentMethod->public_key, $paymentMethod->secret_key);
        $razorpayPayment = $client->order->fetch($walletTransaction->session_id)->payments()->items[0];

        if ($razorpayPayment->status == "authorized" || $razorpayPayment->status == "captured") {


            //has order been paided for before
            if (empty($walletTransaction)) {
                throw new \Exception("Wallet Topup is invalid");
            } else if (!$walletTransaction->isDirty('status') && $walletTransaction->status == "successful") {
                // throw new \Exception("Wallet Topup is has already been paid");
                return;
            }


            try {

                //capture payment
                if (!$razorpayPayment->captured) {
                    $captureResponse = $razorpayPayment->capture(array('amount' => $walletTransaction->amount * 100));
                }
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
            throw new \Exception("Wallet Topup is invalid or has already been paid");
        }
    }

    protected function verifyRazorpaySubscriptionTransaction($subscriptionVendor)
    {

        $paymentMethod = $subscriptionVendor->payment_method;
        $client = new Api($paymentMethod->public_key, $paymentMethod->secret_key);
        $razorpayPayment = $client->order->fetch($subscriptionVendor->transaction_id)->payments()->items[0];

        if ($razorpayPayment->status == "authorized" || $razorpayPayment->status == "captured") {


            //has order been paided for before
            if (empty($subscriptionVendor)) {
                throw new \Exception("Subscription Payment is invalid");
            } else if (!$subscriptionVendor->isDirty('status') && $subscriptionVendor->status == "successful") {
                //throw new \Exception("Subscription Payment is has already been paid");
                return;
            }


            try {

                //capture payment
                if (!$razorpayPayment->captured) {
                    $captureResponse = $razorpayPayment->capture(array('amount' => $subscriptionVendor->subscription->amount * 100));
                }
                DB::beginTransaction();
                $subscriptionVendor->status = "successful";
                $subscriptionVendor->save();
                DB::commit();
                return;
            } catch (\Exception $ex) {
                throw $ex;
            }
        } else {
            throw new \Exception("Subscription Payment is invalid or has already been paid");
        }
    }
}
