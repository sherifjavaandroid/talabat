<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorType;

class VendorTypeController extends Controller
{
    //
    use \App\Traits\GoogleMapApiTrait;

    public function index(Request $request)
    {
        //if request has lat and lng
        //making sure null values are not passed
        if (!is_null($request->latitude) && !is_null($request->longitude)) {
            if ($request->has('latitude') && $request->has('longitude')) {
                $longitude = $request->longitude;
                $latitude = $request->latitude;
                //fetch delivery zones close to the coordinates
                $deliveryZones = \App\Models\DeliveryZone::active()->get();
                $deliveryZonesIds = [];
                foreach ($deliveryZones as $deliveryZone) {
                    $cLatLng = [
                        'lat' => $latitude,
                        'lng' => $longitude
                    ];
                    $inBound = $this->insideBound($cLatLng, $deliveryZone->points);
                    if ($inBound) {
                        $deliveryZonesIds[] = $deliveryZone->id;
                    }
                }

                //fetch vendor types that are in the delivery zones
                return VendorType::whereHas("delivery_zones", function ($query) use ($deliveryZonesIds) {
                    $query->whereIn('delivery_zone_id', $deliveryZonesIds);
                })
                    ->orWhereDoesntHave("delivery_zones")
                    ->active()
                    ->inorder()->get();
            }
        }

        //
        return VendorType::active()->inorder()->get();
    }

    public function show(Request $request, $id)
    {
        return VendorType::find($id);
    }
}