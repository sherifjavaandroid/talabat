<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\VendorPaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{

    public function index(Request $request)
    {

        $vendorId = $request->vendor_id ?? 0;
        $forWallet = $request->for_wallet ?? 0;
        $forPickup = $request->is_pickup ?? 0;
        $paymentMethods = [];

        //
        $vendorPaymentMethodIds = VendorPaymentMethod::where('vendor_id', $vendorId)
            ->when($forPickup, function ($query) use ($forPickup) {
                return $query->where('allow_pickup', 1);
            })
            ->get()
            ->pluck('payment_method_id');
        //
        if (!empty($vendorId) && count($vendorPaymentMethodIds) > 0) {
            $paymentMethods = PaymentMethod::active()
                ->when($forPickup, function ($query) use ($forPickup) {
                    return $query->where('allow_pickup', 1);
                })
                ->whereIn('id', $vendorPaymentMethodIds)->get();
        } else if ($forWallet) {
            $paymentMethods = PaymentMethod::active()
                ->when($forPickup, function ($query) use ($forPickup) {
                    return $query->where('allow_pickup', 1);
                })
                ->where('use_wallet', 1)->get();
        } else {
            $paymentMethods = PaymentMethod::active()
                ->when($forPickup, function ($query) use ($forPickup) {
                    return $query->where('allow_pickup', 1);
                })
                ->when($request->use_taxi, function ($query) use ($request) {
                    return $query->where("use_taxi", 1);
                })->get();
        }
        return response()->json([
            "data" => $paymentMethods
        ], 200);
    }
}