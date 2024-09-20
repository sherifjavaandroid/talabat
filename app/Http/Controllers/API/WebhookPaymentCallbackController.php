<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Traits\WebhookTrait;
use Illuminate\Support\Facades\Http;

class WebhookPaymentCallbackController extends Controller
{

    use WebhookTrait;

    public function index(Request $request, $webhookHash)
    {
        $paymentMethod = PaymentMethod::where('webhook_hash', $webhookHash)->first();
        if (empty($paymentMethod)) {
            return response()->json([
                "message" => "Wrong params",
            ], 200);
        }

        //
        $routeUrl = '';
        $paymentMethodSlug = $paymentMethod->slug;
        if ($paymentMethodSlug == "stripe") {
            $routeUrl = $this->rerouteStripePaymentWehbook();
        } else if ($paymentMethodSlug == "razorpay") {
            $routeUrl = $this->rerouteRazorpayPaymentWehbook();
        } else if ($paymentMethodSlug == "paystack") {
            $routeUrl = $this->reroutePaystackPaymentWehbook();
        } else if ($paymentMethodSlug == "flutterwave") {
            $routeUrl = $this->rerouteFlutterwavePaymentWehbook();
        } else if ($paymentMethodSlug == "paytm") {
            $routeUrl = $this->reroutePayTMPaymentWehbook();
        }
        // } else if ($paymentMethodSlug == "payu") {
        //     $routeUrl =  $this->rerouteFlutterwavePaymentWehbook();
        // }


        if (!empty($routeUrl) && filter_var($routeUrl, FILTER_VALIDATE_URL)) {
            $response = Http::get($routeUrl);
            if ($response->successful()) {
                return response()->json($response->body(), 200);
            } else {
                return response()->json($response->body(), 400);
            }
        } else if ($routeUrl instanceof \Illuminate\Http\Response) {
            return $routeUrl;
        }
        //custom payment
        // logger("webhook called", [
        //     "routeUrl" => $routeUrl,
        //     "url" => url()->current(),
        //     "request" => request()->all(),
        // ]);
        return response()->json([
            "message" => "No response",
        ], 400);
    }
}
