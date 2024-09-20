<?php

namespace App\Traits;

use App\Models\Payment;
use App\Models\SubscriptionVendor;
use App\Models\Wallet;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

trait AbitmediaTrait
{


    public function createAbitmediaPaymentReference($order)
    {
        $paymentMethod = $order->payment_method;
        $paymentlink = "";
        //
        if ($order->payment == null || $order->payment->status != "pending") {

            //

            $ref = Str::random(14);
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->session_id = $ref;
            $payment->ref = $ref;
            $payment->amount = $order->payable_total;



            //create bill
            $response = Http::withToken($paymentMethod->secret_key)
                ->post(
                    'https://cloud.abitmedia.com/api/payments/create-payment-link',
                    [
                        "amount" => $order->payable_total,
                        "amountWithoutTax" => $order->payable_total,
                        "amountWithTax" => 0.00,
                        "tax" => 0.00,
                        "notifyUrl" => route('api.payment.callback', ["code" => $order->code, "status" => "success"]),
                        "description" => "Order payment",
                    ]
                );

            $payment->ref = $response->json()["data"]["link_id"];
            $payment->session_id = $response->json()["data"]["url"];
            $payment->save();

            return $payment->session_id;
        } else {
            $paymentlink = $order->payment->session_id;
        }
        return $paymentlink;
    }

    public function createAbitmediaTopupReference($walletTransaction, $paymentMethod)
    {
        //
        //get collection id
        $response = Http::withToken($paymentMethod->secret_key)
            ->post(
                'https://cloud.abitmedia.com/api/payments/create-payment-link',
                [
                    "amount" => $walletTransaction->amount,
                    "amountWithoutTax" => $walletTransaction->amount,
                    "amountWithTax" => 0.00,
                    "tax" => 0.00,
                    "notifyUrl" => route('api.wallet.topup.callback', ["code" => $walletTransaction->ref, "status" => "success"]),
                    "description" => "Wallet topup payment",
                ]
            );

        $walletTransaction->session_id = $response->json()["data"]["url"];
        $walletTransaction->payment_method_id = $paymentMethod->id;
        $walletTransaction->save();

        return $walletTransaction->session_id;
    }

    public function createAbitmediaSubscribeReference($subscription, $paymentMethod)
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
        //get collection id
        $response = Http::withToken($paymentMethod->secret_key)
            ->post(
                'https://cloud.abitmedia.com/api/payments/create-payment-link',
                [
                    "amount" => $subscription->amount,
                    "amountWithoutTax" => $subscription->amount,
                    "amountWithTax" => 0.00,
                    "tax" => 0.00,
                    "notifyUrl" => route('api.subscription.callback', ["code" => $vendorSubscription->code, "status" => "success"]),
                    "description" => "Subscription payment",
                ]
            );

        $vendorSubscription->transaction_id = $response->json()["data"]["url"];
        $vendorSubscription->save();

        return $vendorSubscription->transaction_id;
    }


    public function verifyAbitmediaTransaction($order, $reference)
    {
        $paymentMethod = $order->payment_method;

        //
        $transactionInfo = Http::withToken($paymentMethod->secret_key)
            ->get('https://cloud.abitmedia.com/api/payments/status-transaction', [
                "reference" => $reference
            ]);

        if ($transactionInfo['status'] == 200 && in_array($transactionInfo['data']['status'], ["Autorizada", "Pagado"])) {

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
            throw new \Exception("Order is invalid or has already been paid -");
        }
    }


    public function verifyAbitmediaTopupTransaction($walletTransaction, $reference)
    {
        $paymentMethod = $walletTransaction->payment_method;

        //
        $transactionInfo = Http::withToken($paymentMethod->secret_key)
            ->get('https://cloud.abitmedia.com/api/payments/status-transaction', [
                "reference" => $reference
            ]);

        if ($transactionInfo['status'] == 200 && in_array($transactionInfo['data']['status'], ["Autorizada", "Pagado"])) {

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

    public function verifyAbitmediaSubscriptionTransaction($vendorSubscription, $reference)
    {
        $paymentMethod = $vendorSubscription->payment_method;

        //
        $transactionInfo = Http::withToken($paymentMethod->secret_key)
            ->get('https://cloud.abitmedia.com/api/payments/status-transaction', [
                "reference" => $reference
            ]);

        if ($transactionInfo['status'] == 200 && in_array($transactionInfo['data']['status'], ["Autorizada", "Pagado"])) {

            //has order been paided for before
            if (empty($vendorSubscription) || $vendorSubscription->status == "successful") {
                throw new \Exception("Subscription payment is invalid or has already been paid");
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
            throw new \Exception("Subscription payment is invalid or has already been paid");
        }
    }
}
