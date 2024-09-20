<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Traits\GoogleMapApiTrait;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{

    use GoogleMapApiTrait;

    public function index(Request $request)
    {

        $deliveryZonesIds = [];
        //IF latitude and longitude are available
        if ($request->latitude && $request->longitude) {
            $deliveryZonesIds = $this->getDeliveryZonesByLocation($request->latitude, $request->longitude);
        }

        //
        if (!empty($request->flash_sale_id)) {
            $flashSaleItems = FlashSaleItem::with('item')->whereFlashSaleId($request->flash_sale_id)->get();
            $flashSaleItemsIds = [];
            //loop through the items and remove items that are not in the delivery zone
            foreach ($flashSaleItems as $flashSaleItem) {
                //get the item of the flash sale item
                $item = $flashSaleItem->item;
                //get the vendor of the item
                $vendor = $item->vendor;
                //get the delivery zones of the vendor
                $vendorDeliveryZones = $vendor->delivery_zones;
                //check if the delivery zone of the vendor is in the delivery zones of the user
                $vendorDeliveryZoneIds = $vendorDeliveryZones->pluck('id')->toArray();
                $isInDeliveryZone = false;
                foreach ($vendorDeliveryZoneIds as $vDeliveryZoneId) {
                    if (in_array($vDeliveryZoneId, $deliveryZonesIds)) {
                        $isInDeliveryZone = true;
                        break;
                    }
                }

                if ($isInDeliveryZone) {
                    $flashSaleItemsIds[] = $flashSaleItem->id;
                }
            }

            //
            $result = FlashSaleItem::with('item')->whereIn('id', $flashSaleItemsIds)->get();
        } else {

            //
            $result = FlashSale::when($request->vendor_type_id, function ($query) use ($request, $deliveryZonesIds) {
                return $query->where("vendor_type_id", $request->vendor_type_id)
                    //add when latitude and longitude are available
                    ->when($request->latitude && $request->longitude, function ($query) use ($request, $deliveryZonesIds) {
                        return $query->whereHas('vendor_type', function ($query) use ($request, $deliveryZonesIds) {
                            return $query->orWhereHas('delivery_zones', function ($query) use ($deliveryZonesIds) {
                                $query->whereIn('delivery_zone_id', $deliveryZonesIds);
                            });
                        });
                    });
            })
                ->active()
                ->notexpired()
                ->get();
        }
        return response()->json($result, 200);
    }
}
