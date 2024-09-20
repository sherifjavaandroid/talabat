<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\SubscriptionVendor;
use App\Models\WalletTransaction;



trait WebhookTrait
{


    public function rerouteStripePaymentWehbook()
    {

        $payload = request()->getContent();

        if (empty($payload)) {
            return response()->json([
                "message" => "No params",
            ], 400);
        }

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        // Handle the event
        if (in_array($event->type, ["payment_intent.succeeded", "charge.captured", "charge.succeeded", "checkout.session.completed"])) {
            //
            $paymentIntent = $event->data->object;
            // logger("paymentIntent", [$paymentIntent]);
            if (!empty($paymentIntent->success_url ?? "")) {
                try {
                    $url = $paymentIntent->redirect->return_url;
                } catch (\Exception $ex) {
                    // logger("error getting return_url", [$ex]);
                    try {
                        $url = $paymentIntent->success_url;
                    } catch (\Exception $ex) {
                        logger("error getting success_url", [$ex]);
                    }
                }
                return $url;
            }
            //
            return response()->json([
                "message" => "Ok, received but not the event we are looking for",
            ], 200);
        } else {
            return response()->json([
                "message" => "No record found",
            ], 400);
        }
    }

    public function rerouteRazorpayPaymentWehbook()
    {

        $requestData = request()->all();
        //
        if (empty($requestData)) {
            return response()->json([
                "message" => "No params",
            ], 400);
        }

        $payloadActions = $requestData['contains'] ?? [];
        if (!in_array("order", $payloadActions)) {
            return response()->json([
                "message" => "No order in webhook",
            ], 200);
        }
        //
        $txRef = $requestData['payload']['order']['entity']['receipt'];
        //check which type of transaction
        $walletTransaction = WalletTransaction::where('ref', $txRef)->first();
        $order = Order::where('code', $txRef)->first();
        $vendorSubscription = SubscriptionVendor::where('code', $txRef)->first();
        //
        if ($order) {
            return route('payment.callback', [
                "code" => $order->code,
                "status" => "success",
            ]);
        } else if ($walletTransaction) {
            return route('wallet.topup.callback', [
                "code" => $walletTransaction->ref,
                "status" => "success",
            ]);
        } else if ($vendorSubscription) {
            return route('subscription.callback', [
                "code" => $vendorSubscription->code,
                "status" => "success",
            ]);
        }

        return response()->json([
            "message" => "No record found",
        ], 200);
    }

    public function rerouteFlutterwavePaymentWehbook()
    {
        $requestData = request()->all();
        if (empty($requestData)) {
            return response()->json([
                "message" => "No params",
            ], 400);
        }
        $transactionId = $requestData['data']['id'];
        $txRef = $requestData['data']['tx_ref'];
        //check which type of transaction
        $walletTransaction = WalletTransaction::where('session_id', $txRef)->first();
        $order = Order::whereHas('payment', function ($q) use ($txRef) {
            $q->where('ref', $txRef);
        })->first();
        $vendorSubscription = SubscriptionVendor::where('code', $txRef)->first();
        //
        if ($order) {
            return route('payment.callback', [
                "code" => $order->code,
                "status" => "success",
                "transaction_id" => $transactionId,
                "tx_ref" => $txRef,
            ]);
        } else if ($walletTransaction) {
            return route('wallet.topup.callback', [
                "code" => $walletTransaction->ref,
                "status" => "success",
                "transaction_id" => $transactionId,
                "tx_ref" => $txRef,
            ]);
        } else if ($vendorSubscription) {
            return route('subscription.callback', [
                "code" => $vendorSubscription->code,
                "status" => "success",
                "transaction_id" => $transactionId,
                "tx_ref" => $txRef,
            ]);
        }

        return response()->json([
            "message" => "No record found",
        ], 400);
    }

    public function reroutePaystackPaymentWehbook()
    {
        $input = @file_get_contents("php://input");
        $requestData = json_decode($input, true);
        $requestData = request()->all();

        if (empty($requestData)) {
            return response()->json([
                "message" => "No params",
            ], 400);
        }
        $txRef = $requestData['data']['transaction']['reference'] ?? $requestData['data']['invoice_code'];
        //check which type of transaction
        $walletTransaction = WalletTransaction::where('session_id', $txRef)->first();
        $order = Order::whereHas('payment', function ($q) use ($txRef) {
            $q->where('ref', $txRef);
        })->first();
        $vendorSubscription = SubscriptionVendor::where('code', $txRef)->first();
        //
        if ($order) {
            return route('payment.callback', [
                "code" => $order->code,
                "status" => "success",
            ]);
        } else if ($walletTransaction) {
            return route('wallet.topup.callback', [
                "code" => $walletTransaction->ref,
                "status" => "success",
            ]);
        } else if ($vendorSubscription) {
            return route('subscription.callback', [
                "code" => $vendorSubscription->code,
                "status" => "success",
            ]);
        }

        return response()->json([
            "message" => "No record found",
        ], 400);
    }


    public function reroutePayTMPaymentWehbook()
    {
        $requestData = request()->all();
        if (!request()->has('ORDERID')) {
            return response()->json([
                "message" => "No params",
            ], 400);
        }

        $txRef = $requestData['ORDERID'];
        //check which type of transaction
        $walletTransaction = WalletTransaction::where('ref', $txRef)->first();
        $order = Order::where('code', $txRef)->first();
        $vendorSubscription = SubscriptionVendor::where('code', $txRef)->first();
        //
        if ($order) {
            return route('payment.callback', [
                "code" => $order->code,
                "status" => "success",
            ]);
        } else if ($walletTransaction) {
            return route('wallet.topup.callback', [
                "code" => $walletTransaction->ref,
                "status" => "success",
            ]);
        } else if ($vendorSubscription) {
            return route('subscription.callback', [
                "code" => $vendorSubscription->code,
                "status" => "success",
            ]);
        }

        return response()->json([
            "message" => "No record found",
        ], 400);
    }
}