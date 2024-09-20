<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use App\Models\Vendor;
use App\Traits\GoogleMapApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryAddressController extends Controller
{

    use GoogleMapApiTrait;


    public function index(Request $request)
    {

        $vendor = Vendor::find($request->vendor_id);

        if ($request->action == "default") {
            //default delivery address
            $deliveryAddresses = DeliveryAddress::where('user_id', "=", Auth::id())->where('is_default', 1)
                ->when($vendor, function ($query) use ($vendor) {
                    return $query->distance($vendor->latitude, $vendor->longitude);
                })->limit(1)->get();
        } else {

            $deliveryAddresses = DeliveryAddress::where('user_id', "=", Auth::id())
                ->when($vendor, function ($query) use ($vendor) {
                    return $query->distance($vendor->latitude, $vendor->longitude);
                })->orderBy('updated_at', 'DESC')->get();
        }

        //
        if (!empty($request->vendor_id)) {

            $vendors = [];
            if (!empty($request->vendor_ids)) {
                $vendors = Vendor::whereIn("id", json_decode($request->vendor_ids, true))->get();
            }

            foreach ($deliveryAddresses as $deliveryAddress) {

                //for multiple selected vendors
                if (!empty($request->vendor_ids)) {

                    foreach ($vendors as $mVendor) {
                        $deliveryAddress->can_deliver = $this->locationInZone($mVendor, $deliveryAddress);
                        //once at least a delivery addrss can't be delivered to to, return can_deliver false and
                        //stop checking other vendor
                        if (!$deliveryAddress->can_deliver) {
                            break;
                        }
                    }
                } else {
                    $deliveryAddress->can_deliver = $this->locationInZone($vendor, $deliveryAddress);
                }
            }
        }



        if ($request->action == "default") {
            return response()->json($deliveryAddresses->first() ?? null, 200);
        } else {
            return response()->json([
                "data" => $deliveryAddresses,
                "vendor" => $vendor,
            ], 200);
        }
    }

    public function show(Request $request, $id)
    {

        $vendor = Vendor::find($request->vendor_id);
        $deliveryAddress = DeliveryAddress::where('user_id', "=", Auth::id())
            ->where('id', $id)
            ->when($request->vendor_id, function ($query) use ($vendor) {
                return $query->distance($vendor->latitude, $vendor->longitude);
            })->first();
        //
        if (!empty($request->vendor_id)) {
            $deliveryAddress->can_deliver = $this->locationInZone($vendor, $deliveryAddress);
        }

        return response()->json([
            "data" => $deliveryAddress,
            "vendor" => $vendor,
        ], 200);
    }

    public function store(Request $request)
    {

        try {

            $model = new DeliveryAddress();
            $model->fill($request->all());
            $model->user_id = Auth::id();

            //fetch more data like city,country etc
            if (empty($model->city) || empty($model->country) || empty($model->state)) {
                $geoCoder = new GeocoderController();
                $request = new \Illuminate\Http\Request();
                $request->replace(['lat' => $model->latitude, 'lng' => $model->longitude]);
                $addresses = $geoCoder->forward($request)->getData()->data;
                $addresses = json_decode(json_encode($addresses), true);
                $model->city = $addresses[0]["subLocality"] ?? $addresses[0]["locality"];
                $model->state = $addresses[0]["administrative_area_level_1"];
                $model->country = $addresses[0]["country"];
            }
            //save the model
            $model->save();

            return response()->json([
                "message" => __("Delivery address created successfully"),
                "data" => $model,
            ], 200);
        } catch (\Exception $ex) {

            return response()->json([
                "message" => $ex->getMessage() ?? __("Delivery address creation failed")
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {

        try {

            $model = DeliveryAddress::where('user_id', Auth::id())->where('id', $id)->firstorfail();
            $model->fill($request->all());
            //fetch more data like city,country etc
            if (empty($model->city) || empty($model->country) || empty($model->state)) {
                $geoCoder = new GeocoderController();
                $request = new \Illuminate\Http\Request();
                $request->replace(['lat' => $model->latitude, 'lng' => $model->longitude]);
                $addresses = $geoCoder->forward($request)->getData()->data;
                $addresses = json_decode(json_encode($addresses), true);
                $model->city = $addresses[0]["subLocality"] ?? $addresses[0]["locality"];
                $model->state = $addresses[0]["administrative_area_level_1"];
                $model->country = $addresses[0]["country"];
            }
            //save the model
            $model->save();

            return response()->json([
                "message" => __("Delivery address updated successfully")
            ], 200);
        } catch (\Exception $ex) {

            return response()->json([
                "message" => __("Delivery address update failed")
            ], 400);
        }
    }

    public function destroy(Request $request, $id)
    {

        try {

            DeliveryAddress::where('user_id', Auth::id())->where('id', $id)->firstorfail()->delete();
            return response()->json([
                "message" => __("Delivery address deleted successfully")
            ], 200);
        } catch (\Exception $ex) {
            logger("Erro", [$ex]);
            return response()->json([
                "message" => __("No Delivery address Found")
            ], 400);
        }
    }
}
