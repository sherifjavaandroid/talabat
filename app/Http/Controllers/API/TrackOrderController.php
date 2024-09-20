<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{

    //
    public function track(Request $request)
    {

        $vendorTypeId = $request->vendor_type_id;

        //
        try {
            $order = Order::fullData()->where("code", $request->code)
                ->when($vendorTypeId, function ($query) use ($vendorTypeId) {
                    return $query->whereHas("vendor", function ($query) use ($vendorTypeId) {
                        return $query->where('vendor_type_id', $vendorTypeId);
                    });
                })->firstOrFail();

            //
            return $order;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $error) {
            return response()->json([
                "message" => __("Invalid tracking code")
            ], 400);
        }
    }
}
