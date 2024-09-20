<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;
use AnthonyMartin\GeoLocation\GeoPoint;
use App\Services\PolygonPointLocationService;

trait GoogleMapApiTrait
{


    public function getTotalDistanceFromGoogle($originLocation, $destinationLocations)
    {

        $googleMapDistanceResposne = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            "key" => env('googleMapKey'),
            "origins" => $originLocation,
            "destinations" => $destinationLocations,
        ]);

        if ($googleMapDistanceResposne->successful()) {
            $distance = 0;
            $distanceElements = $googleMapDistanceResposne->json()["rows"][0]["elements"];

            foreach ($distanceElements as $distanceElement) {
                $distance += $distanceElement["distance"]["value"];
            }

            return $distance / 1000;
        } else {
            throw new Exception(__("An error occured on our server"), 1);
        }
    }

    public function getLinearDistance($originLocation, $destinationLocations)
    {
        $lat1 = (float) explode(",", $originLocation)[0];
        $lon1 = (float) explode(",", $originLocation)[1];
        //
        $lat2 = (float) explode(",", $destinationLocations)[0];
        $lon2 = (float) explode(",", $destinationLocations)[1];
        // $lat1, $lon1, $lat2, $lon2
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;
        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;
        return $km;
    }



    //
    public function getRelativeDistance($originLocation, $destinationLocations)
    {
        try {
            if (setting('enableGoogleDistance', 0)) {
                $distance = $this->getTotalDistanceFromGoogle($originLocation, $destinationLocations);
            } else {
                $distance = $this->getLinearDistance($originLocation, $destinationLocations);
            }
        } catch (\Exception $ex) {
            $distance = $this->getLinearDistance($originLocation, $destinationLocations);
        }

        //
        return $distance;
    }

    public function getEarthDistance($lat, $lng)
    {
        $geopointA = new GeoPoint($lat, $lng);
        $geopointB = new GeoPoint(0.00, 0.00);
        return $geopointA->distanceTo($geopointB, 'kilometers');
    }




    //
    public function locationInZone($vendor, $deliveryAddress, $forCheck = false)
    {
        //linear distance check
        if (empty($vendor->delivery_zones) || count($vendor->delivery_zones) <= 0) {
            if (!empty($vendor->latitude) && !empty($vendor->longitude)) {
                $originLatLng = "" . $vendor->latitude . "," . $vendor->longitude . "";
                $destinationLatLng = "" . $deliveryAddress->latitude . "," . $deliveryAddress->longitude . "";
                $deliveryAddressDistanceToVendor = $this->getLinearDistance($originLatLng, $destinationLatLng);
                return $deliveryAddressDistanceToVendor <= $vendor->delivery_range;
            } else {
                return false;
            }
        }
        if ($forCheck) {

            $canDeliver = false;
            foreach ($vendor->delivery_zones as $delivery_zone) {

                $originLatLng = "" . $delivery_zone->latitude . "," . $delivery_zone->longitude . "";
                $destinationLatLng = "" . $deliveryAddress->latitude . "," . $deliveryAddress->longitude . "";
                $deliveryAddressDistanceToVendor = $this->getLinearDistance($originLatLng, $destinationLatLng);
                $canDeliver = $deliveryAddressDistanceToVendor <= $delivery_zone->radius;
                //
                if ($canDeliver) {
                    break;
                }
            }
            return $canDeliver;
        }
        //
        $canDeliver = false;
        foreach ($vendor->delivery_zones as $delivery_zone) {

            $canDeliver = $this->insideBound(
                [
                    "lat" => $deliveryAddress->latitude,
                    "lng" => $deliveryAddress->longitude,
                ],
                $delivery_zone->points
            );
            //
            if ($canDeliver) {
                break;
            }
        }
        return $canDeliver;
    }


    function insideBound($cLatLng, $points)
    {

        $polygonPointLocationService = new PolygonPointLocationService();
        $canDeliver = false;
        try {
            $point = "{$cLatLng['lat']} {$cLatLng['lng']}";
            $polygon = $points->map(function ($point) {
                return "{$point->lat} {$point->lng}";
            })->toArray();
            $firstPoint = $points->first();
            array_push($polygon, "{$firstPoint->lat} {$firstPoint->lng}");
            //
            $result = $polygonPointLocationService->pointInPolygon($point, $polygon);
            if ($result != null && $result == "inside") {
                $canDeliver = true;
            } else {
                $canDeliver = false;
            }
        } catch (\Exception $ex) {
            logger("error with new bound check", [$ex]);
            $canDeliver = false;
        }
        return $canDeliver;
    }

    /*
    OLD VERISON
    function insideBound($point, $fenceArea)
    {

        $x = $point['lat'];
        $y = $point['lng'];

        $inside = false;
        for ($i = 0, $j = count($fenceArea) - 1; $i <  count($fenceArea); $j = $i++) {
            $xi = $fenceArea[$i]['lat'];
            $yi = $fenceArea[$i]['lng'];
            $xj = $fenceArea[$j]['lat'];
            $yj = $fenceArea[$j]['lng'];

            $intersect = (($yi > $y) != ($yj > $y))
                && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
            if ($intersect) {
                $inside = true;
                break;
            }
        }
        return $inside;
    }
*/

    function getCenter($coord_array)
    {
        $i = 0;
        $center = $coord_array[0];
        $mlat = 0;
        $mlng = 0;
        unset($coord_array[0]);
        foreach ($coord_array as $key => $coord) {
            $plat = $coord["lat"];
            $plng = $coord["lng"];
            $clat = $center["lat"];
            $clng = $center["lng"];
            $mlat = ($plat + ($clat * $i)) / ($i + 1);
            $mlng = ($plng + ($clng * $i)) / ($i + 1);
            $center = ["lat" => $mlat, "lng" => $mlng];
            $i++;
        }
        return array($mlat, $mlng);
    }




    //fetch all delivery_zones with provided latitude and longitude
    public function getDeliveryZonesByLocation($latitude, $longitude)
    {
        $deliveryZonesIds = [];
        //Zone_filter: if request has latitute and longitude
        if ($latitude != null && $longitude != null) {
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
        }
        return $deliveryZonesIds;
    }
}
