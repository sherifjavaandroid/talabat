<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\Payment;
use App\Models\SubscriptionVendor;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

trait StripeTrait
{


    public function createStripePaymentSession($order)
    {

        if ($order->payment == null || $order->payment->status != "pending") {
            $paymentMethod = $order->payment_method;
            $lineItems = [];
            if ($order->type != "parcel") {
                array_push($lineItems, [
                    'price_data' => [
                        'currency' => \Str::lower($order->getCurrencyCode()),
                        'product_data' => [
                            'name' => __("Order Payment"),
                        ],
                        'unit_amount' => $order->payable_total * 100,
                    ],
                    'quantity' => 1,
                ]);
            } else {
                array_push($lineItems, [
                    'price_data' => [
                        'currency' => \Str::lower($order->getCurrencyCode()),
                        'product_data' => [
                            'name' => $order->package_type->name,
                        ],
                        'unit_amount' => $order->payable_total * 100,
                    ],
                    'quantity' => 1,
                ]);
            }

            \Stripe\Stripe::setApiKey($paymentMethod->secret_key);


            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.callback', ["code" => $order->code, "status" => "success"]),
                'cancel_url' => route('payment.callback', ["code" => $order->code, "status" => "cancelled"]),
            ]);


            //
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->session_id = $session->id;
            $payment->amount = $order->total;
            $payment->save();

            return $session->id;
        } else {
            return $order->payment->session_id;
        }
    }

    public function createStripeTopupSession($walletTransaction, $paymentMethod)
    {

        if (empty($walletTransaction->session_id) && $walletTransaction->status == "pending") {

            $lineItems = [];
            array_push($lineItems, [
                'price_data' => [
                    'currency' => \Str::lower(setting('currencyCode', 'USD')),
                    'product_data' => [
                        'name' => "Wallet Topup",
                    ],
                    'unit_amount' => $walletTransaction->amount * 100,
                ],
                'quantity' => 1,
            ]);

            \Stripe\Stripe::setApiKey($paymentMethod->secret_key);


            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('wallet.topup.callback', ["code" => $walletTransaction->ref, "status" => "success"]),
                'cancel_url' => route('wallet.topup.callback', ["code" => $walletTransaction->ref, "status" => "cancelled"]),
            ]);


            //
            $walletTransaction->session_id = $session->id;
            $walletTransaction->payment_method_id = $paymentMethod->id;
            $walletTransaction->save();

            return $session->id;
        } else {
            return $walletTransaction->session_id;
        }
    }

    public function createStripeSubscribeSession($subscription, $paymentMethod)
    {



        $vendorSubscription = new SubscriptionVendor();
        $vendorSubscription->code = \Str::random(12);
        //payment status
        $vendorSubscription->status = "pending";
        $vendorSubscription->payment_method_id = $paymentMethod->id;
        $vendorSubscription->subscription_id = $subscription->id;
        $vendorSubscription->vendor_id = \Auth::user()->vendor_id;
        $vendorSubscription->save();



        $lineItems = [];
        array_push($lineItems, [
            'price_data' => [
                'currency' => \Str::lower(setting('currencyCode', 'USD')),
                'product_data' => [
                    'name' => "Vendor Subscription",
                ],
                'unit_amount' => $subscription->amount * 100,
            ],
            'quantity' => 1,
        ]);

        \Stripe\Stripe::setApiKey($paymentMethod->secret_key);


        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('subscription.callback', ["code" => $vendorSubscription->code, "status" => "success"]),
            'cancel_url' => route('subscription.callback', ["code" => $vendorSubscription->code, "status" => "cancelled"]),
        ]);

        $vendorSubscription->transaction_id = $session->id ?? "";
        $vendorSubscription->save();

        return $session->id;
    }


    //Stripe verification
    protected function verifyStripeTransaction($order)
    {

        $paymentMethod = $order->payment_method;
        $stripe = new \Stripe\StripeClient($paymentMethod->secret_key);

        //
        $response = $stripe->checkout->sessions->retrieve(
            $order->payment->session_id,
            []
        );

        if ($response->payment_status == "paid") {

            $payment = Payment::where('session_id', $response->id)->first();

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

    protected function verifyStripeTopupTransaction($walletTransaction)
    {

        $paymentMethod = $walletTransaction->payment_method;
        $stripe = new \Stripe\StripeClient($paymentMethod->secret_key);

        //
        $response = $stripe->checkout->sessions->retrieve(
            $walletTransaction->session_id,
            []
        );

        if ($response->payment_status == "paid") {


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
            throw new \Exception("Wallet Topup is invalid or has already been paid");
        }
    }

    protected function verifyStripeSubscriptionTransaction($subscriptionVendor)
    {


        $paymentMethod = $subscriptionVendor->payment_method;
        $stripe = new \Stripe\StripeClient($paymentMethod->secret_key);

        //
        $response = $stripe->checkout->sessions->retrieve(
            $subscriptionVendor->transaction_id,
            []
        );

        if ($response->payment_status == "paid") {


            //has order been paided for before
            if (empty($subscriptionVendor)) {
                throw new \Exception("Subscription Payment is invalid");
            } else if (!$subscriptionVendor->isDirty('status') && $subscriptionVendor->status == "successful") {
                //throw new \Exception("Subscription Payment is has already been paid");
                return;
            }


            try {
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
