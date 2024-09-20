<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\CityVendor;
use App\Models\StateVendor;
use App\Models\CountryVendor;
use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\OrderStop;
use App\Models\PackageTypePricing;
use App\Models\Vendor;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Traits\GeoBoundaryCheckTrait;
use App\Traits\GoogleMapApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PackageOrderController extends Controller
{
    use GoogleMapApiTrait;
    use GeoBoundaryCheckTrait;

    //fetch vendors that service the provided locations
    public function fetchVendors(Request $request)
    {

        try {

            // logger("fetchVendors request Data",[$request->all()]);
            //request data
            //locations
            //vendor_type_id
            //package_type_id

            //fetch vendor with the provided vendor type
            $vendors = Vendor::with('package_types_pricing')->where('vendor_type_id', $request->vendor_type_id)
                ->whereHas('package_types_pricing', function ($query) use ($request) {
                    $query->where('package_type_id', $request->package_type_id);
                })
                ->active()
                ->get();


            //loop through vendors and create 2 array of vendor that support all locations and vendor that support at least one location
            $vendorsWithAllLocations = [];
            $vendorsWithAtLeastOneLocation = [];

            foreach ($vendors as $vendor) {
                //check if vendor service all locations
                $vendorServiceAllLocations = true;
                $unSupportedLocations = [];
                //
                foreach ($request->locations as $location) {
                    //if stop has not city,state or country info fetch
                    $location = $this->fetchMissingLocationInfo($location);
                    //check if vendor service the city
                    if (!$this->isStopServiceByVendor($vendor->id, $location)) {
                        $vendorServiceAllLocations = false;
                        $unSupportedLocations[] = $location;
                        break;
                    }
                }

                //check if vendor service all locations
                if ($vendorServiceAllLocations) {
                    $vendorsWithAllLocations[] = $vendor->toArray();
                } else {
                    $vendor['unsupported_locations'] = $unSupportedLocations;
                    $vendorsWithAtLeastOneLocation[] = $vendor->toArray();
                }
            }


            //
            if ($request->correct_only == null || $request->correct_only == true) {
                return response()->json([
                    "vendors" => $vendorsWithAllLocations,
                ], 200);
            }


            //cast to collection of Vendor model
            $vendorsWithAllLocations = collect($vendorsWithAllLocations)->map(function ($vendor) {
                return new Vendor($vendor);
            });
            $vendorsWithAtLeastOneLocation = collect($vendorsWithAtLeastOneLocation)->map(function ($vendor) {
                return new Vendor($vendor);
            });

            //return vendors with all locations first
            $allVendors = $vendorsWithAllLocations->merge($vendorsWithAtLeastOneLocation);
            $allVendors = $allVendors->toArray();

            //
            return response()->json([
                "vendors" => $allVendors,
            ], 200);

            //
        } catch (\Exception $ex) {
            return response()->json([
                "message" => $ex->getMessage(),
            ], 400);
        }
    }

    //
    public function summary(Request $request)
    {

        try {
            $ignoreCheck = (bool) $request->ignore_check ?? false;
            //
            if (!$ignoreCheck && !empty($request->pickup_location_id) || !empty($request->dropoff_location_id)) {
                //check if delivery addresses are with vendor cities
                if (!$this->isCityAllowedInSystem($request->pickup_location_id)) {
                    return response()->json([
                        "message" => __("System does not service pickup location"),
                    ], 400);
                }
                //check iof city is even in the system
                else if (!$this->isCityAllowedInSystem($request->dropoff_location_id)) {
                    return response()->json([
                        "message" => __("System does not service drop-off location"),
                    ], 400);
                }


                //check if vendor service the city
                if (!$this->isCityAllowedByVendor($request->vendor_id, $request->pickup_location_id)) {
                    return response()->json([
                        "message" => __("Vendor does not service pickup location"),
                    ], 400);
                } else if (!$this->isCityAllowedByVendor($request->vendor_id, $request->dropoff_location_id)) {
                    return response()->json([
                        "message" => __("Vendor does not service drop-off location"),
                    ], 400);
                }
            }
            //
            $deliveryLocationDistance = 0;
            $originLatLng = "";
            $destinationLatLngs = "";


            //for stops
            if (!empty($request->stops)) {

                $stops = $request->stops;
                try {
                    $totalStops = count($stops);
                } catch (\Exception $ex) {
                    $stops = json_decode($request->stops, true);
                    $request->merge(["stops" => $stops]);
                    $totalStops = count($stops);
                }
                $newTotalStops = $totalStops - 1;
                //
                for ($i = 0; $i < $newTotalStops; $i++) {
                    //
                    $stop = $stops[$i];
                    $nextStop = $stops[$i + 1];

                    //check iof city is even in the system
                    if (!$ignoreCheck && !$this->isCityAllowedInSystem($stop["id"])) {
                        return response()->json([
                            "message" => __("System does not service stop location"),
                        ], 400);
                    }


                    //check if vendor service the city
                    if (!$ignoreCheck && !$this->isCityAllowedByVendor($request->vendor_id, $stop["id"])) {
                        return response()->json([
                            "message" => __("Vendor does not service stop location"),
                        ], 400);
                    }


                    //sum up the stop distance
                    $deliveryLocationDistance += $this->getDistanceBetweenStop($stop["id"], $nextStop["id"]);
                }
            } else {

                //drop-off location distance calculation
                $dropoffLocation = $this->getDeliveryAddress($request->dropoff_location_id);
                $deliveryLocationDistance = DeliveryAddress::distance($dropoffLocation->latitude, $dropoffLocation->longitude)
                    ->where('id', $request->pickup_location_id)
                    ->first()
                    ->distance;
            }


            if (setting('enableGoogleDistance', 0)) {

                //clear
                $deliveryLocationDistance = 0;

                //
                if (!empty($request->stops)) {

                    $stops = $request->stops;
                    $totalStops = count($stops);
                    $newTotalStops = $totalStops - 1;

                    for ($i = 0; $i < $newTotalStops; $i++) {
                        //
                        $stop = $stops[$i];
                        $nextStop = $stops[$i + 1];
                        //for google map distance calculations
                        $originAddress = $this->getDeliveryAddress($stop["id"]);
                        $destinationAddress = $this->getDeliveryAddress($nextStop["id"]);
                        //fpormat fpr google use
                        $originLatLng = "" . $originAddress->latitude . "," . $originAddress->longitude;
                        $destinationLatLngs = "" . $destinationAddress->latitude . "," . $destinationAddress->longitude;

                        //
                        $deliveryLocationDistance += $this->getTotalDistanceFromGoogle(
                            $originLatLng,
                            $destinationLatLngs
                        );
                    }
                } else {
                    $pickupLocation = $this->getDeliveryAddress($request->pickup_location_id);
                    $dropoffLocation = $this->getDeliveryAddress($request->dropoff_location_id);
                    $originLatLng = "" . $pickupLocation->latitude . "," . $pickupLocation->longitude;
                    $destinationLatLngs = "" . $dropoffLocation->latitude . "," . $dropoffLocation->longitude;
                    //
                    $deliveryLocationDistance = $this->getTotalDistanceFromGoogle(
                        $originLatLng,
                        $destinationLatLngs
                    );
                }
            }


            //
            $packageTypePricing = PackageTypePricing::where('vendor_id', $request->vendor_id)
                ->where('package_type_id', $request->package_type_id)->first();


            //calculation time
            $tax = Vendor::find($request->vendor_id)->tax;
            $tax ??= setting('finance.generalTax', 0);
            $sizeAmount = 0;
            $distanceAmount = 0;
            $totalAmount = 0;

            //calculate the weigth price
            if ($packageTypePricing->price_per_kg) {
                $sizeAmount = $packageTypePricing->size_price * $request->weight;
            } else {
                $sizeAmount = $packageTypePricing->size_price;
            }


            //calculate the distance price
            if ($packageTypePricing->price_per_km) {
                $distanceAmount = $packageTypePricing->distance_price * $deliveryLocationDistance;
            } else {
                $distanceAmount = $packageTypePricing->distance_price;
            }
            $distanceAmount += $packageTypePricing->base_price;
            //multiple stop fee
            if (!empty($request->stops) && count($request->stops) > 2) {
                $distanceAmount += ($packageTypePricing->multiple_stop_fee) * count($request->stops);
            }

            //total amount
            $subTotalAmount = $distanceAmount + $sizeAmount;
            $discount = 0;
            $coupon = null;
            //if coupon is used
            if (!empty($request->coupon_code)) {

                //
                $vendor = Vendor::find($request->vendor_id);
                $coupon = Coupon::where('code', $request->coupon_code)->first();

                if ($coupon == null) {
                    throw new \Exception(__("Coupon code is invalid"));
                }
                //check if the coupon vendor type is same as the selected vendor
                if ($coupon->vendor_type_id != null && $coupon->vendor_type_id != $vendor->vendor_type_id) {
                    throw new \Exception(__("Coupon code is invalid for this vendor type"));
                }

                //also check if user has use this coupon before and if the coupon is not reusable
                $usedTimes = CouponUser::where('coupon_id', $coupon->id)
                    ->where('user_id', Auth::id())
                    ->count() ?? 0;
                if ($coupon->times != null && $usedTimes >= $coupon->times) {
                    throw new \Exception(__("You have exceeded number of use"), 1);
                }

                //check if vendor id is in the list of allowed vendors for this coupon
                $vendorIds = $coupon->vendors->pluck('id')->toArray();
                if (count($vendorIds) > 0 && !in_array($vendor->id, $vendorIds)) {
                    throw new \Exception(__("Coupon code is invalid for this vendor"));
                }

                if ($coupon) {
                    if ($coupon->percentage) {
                        $discount = ($coupon->discount / 100) * $subTotalAmount;
                    } else {
                        $discount = $coupon->discount;
                    }

                    //check if discount is greater than the max discount
                    if ($coupon->max_coupon_amount != null && $discount > $coupon->max_coupon_amount) {
                        $discount = $coupon->max_coupon_amount;
                    }

                    //check if subtotal is greater than the min order amount
                    if ($coupon->min_order_amount != null && $subTotalAmount < $coupon->min_order_amount) {
                        $discount = 0;
                    }


                    //
                    // $subTotalAmount -= $discount;
                }
            }




            //
            $taxAmount = ($tax / 100) * $subTotalAmount;
            $totalAmount = $taxAmount + ($subTotalAmount - $discount);
            //vendor fees
            $vendor = Vendor::find($request->vendor_id);
            $totalFee = 0;
            $vendorFees = [];

            foreach ($vendor->fees as $fee) {
                $feeAmount = 0;
                if ($fee->percentage) {
                    $totalFee += $feeAmount = ($fee->value / 100) * $subTotalAmount;
                } else {
                    $totalFee += $feeAmount = $fee->value;
                }
                $vendorFees[] = [
                    "id" => $fee->id,
                    "name" => $fee->name,
                    "value" => $fee->value,
                    "percentage" => $fee->percentage,
                    "amount" => $feeAmount,
                ];
            }
            $totalAmount += $totalFee;

            $result = [
                "delivery_fee" => $distanceAmount,
                "package_type_fee" => $sizeAmount,
                "distance" => $deliveryLocationDistance,
                "sub_total" => $subTotalAmount,
                "discount" => $discount,
                "tax" => (float)$taxAmount,
                "tax_rate" => (float)$tax,
                "fees" => (float)$totalFee,
                "vendor_fees" => $vendorFees,
                "total" => $totalAmount,
                "coupon" => $coupon ?? null,
            ];
            $token = \Crypt::encrypt($result);
            $result["token"] = $token;


            return response()->json($result);
        } catch (\Exception $ex) {
            logger("Order calculation error", [$ex]);
            return response()->json([
                "message" => $ex->getMessage() ?? __("Order calculation error"),
            ], 400);
        }
    }


    public function verifyOrderStop(Request $request, $id)
    {
        $orderStop = OrderStop::find($id);
        if (empty($orderStop)) {
            return response()->json([
                "message" => __("Invalid order stop"),
            ], 400);
        }

        //
        try {

            \DB::beginTransaction();
            $orderStop->verified = true;
            $orderStop->save();
            //for signature
            if ($request->hasFile("signature")) {
                $orderStop->addMedia($request->signature->getRealPath())->toMediaCollection("proof");
            }

            \DB::commit();

            return response()->json([
                "message" => __("Order stop verified"),
                "order" => Order::fullData()->where('id', $orderStop->order_id)->first(),
            ], 200);
        } catch (\Exception $ex) {
            \DB::rollback();
            logger("Order stop verification error", [$ex]);
            return response()->json([
                "message" => __("Error verifying order stop"),
            ], 400);
        }
    }


    //
    public function getDeliveryAddress($id): ?DeliveryAddress
    {
        return DeliveryAddress::find($id);
    }


    public function isCityAllowedInSystem($id)
    {
        $deliveryAddress = DeliveryAddress::find($id);
        $lat = $deliveryAddress->latitude;
        $lng = $deliveryAddress->longitude;
        //check iof city is even in the system
        $deliveryAddressCity = City::where('name', $deliveryAddress->city)->first();
        if (!empty($deliveryAddressCity)) {
            return true;
        } else {
            //get city where the location can be found
            $country = $this->inAnyCountryBoundary($lat, $lng);
            $state = $this->inAnyStateBoundary($lat, $lng, $country->id ?? null);
            $city = $this->inAnyCityBoundary($lat, $lng, $state->id ?? null);
            if ($city != null && !empty($city)) {
                return true;
            }
        }

        //now check if delivery state is in the system
        $deliveryAddressState = State::where('name', $deliveryAddress->state)->first();
        if (!empty($deliveryAddressState)) {
            return true;
        } else {
            //get state where the location can be found
            $country = $this->inAnyCountryBoundary($lat, $lng);
            $state = $this->inAnyStateBoundary($lat, $lng, $country->id ?? null);
            if ($state != null && !empty($state)) {
                return true;
            }
        }


        //now check if delivery country is in the system
        $deliveryAddressCountry = Country::where('name', $deliveryAddress->country)->first();
        if (!empty($deliveryAddressCountry)) {
            return true;
        } else {
            //get country where the location can be found
            $country = $this->inAnyCountryBoundary($lat, $lng);
            if ($country != null && !empty($country)) {
                return true;
            }
        }


        return false;
    }

    public function isCityAllowedByVendor($vendorId, $id)
    {
        $deliveryAddress = DeliveryAddress::find($id);

        $city = $deliveryAddress->city;
        $state = $deliveryAddress->state;
        $country = $deliveryAddress->country;
        $lat = $deliveryAddress->latitude;
        $lng = $deliveryAddress->longitude;

        try {
            //city check
            $found = $this->vendorCityLocationCheck($city, $country, $lat, $lng, $vendorId);
            if ($found) {
                return true;
            }
        } catch (\Exception $e) {
            // logger("Error city check", [$e]);
        }

        try {
            //state check
            $found = $this->vendorStateLocationCheck($state, $country, $lat, $lng, $vendorId);
            if ($found) {
                return true;
            }
        } catch (\Exception $e) {
            // logger("Error state check", [$e]);
        }

        try {
            //country check
            $found = $this->vendorCountryLocationCheck($country, $lat, $lng, $vendorId);
            if ($found) {
                return true;
            }
        } catch (\Exception $e) {
            // logger("Error country check", [$e]);
        }

        return false;
    }

    //for raw city, state and country
    public function isStopServiceByVendor($vendorId, $location)
    {
        $city = $location["city"];
        $state = $location["state"];
        $country = $location["country"];
        //
        $lat = $location["latitude"] ?? $location["lat"];
        $lng = $location["longitude"] ?? $location["lng"] ?? $location["long"];
        try {
            //check iof city is even in the system
            $found = $this->vendorCityLocationCheck($city, $country, $lat, $lng, $vendorId);
            if ($found) {
                return true;
            }
        } catch (\Exception $e) {
            logger("Error city check", [$e]);
        }

        try {
            //now check if delivery state is in the system
            $found = $this->vendorStateLocationCheck($state, $country, $lat, $lng, $vendorId);
            if ($found) {
                return true;
            }
        } catch (\Exception $e) {
            logger("Error state check", [$e]);
        }


        try {
            //now check if delivery country is in the system
            $found = $this->vendorCountryLocationCheck($country, $lat, $lng, $vendorId);
            if ($found) {
                return true;
            }
        } catch (\Exception $e) {
            logger("Error country check", [$e]);
        }

        return false;
    }

    public function fetchMissingLocationInfo($location)
    {
        //location is array
        //if city or state or country is missing
        $isCityMissing = empty($location["city"]);
        $isStateMissing = empty($location["state"]);
        $isCountryMissing = empty($location["country"]);
        $administrtiveAreaMissing = $isCityMissing || $isStateMissing || $isCountryMissing;
        //
        if ($administrtiveAreaMissing) {
            //
            $city = null;
            $state = null;
            $country = null;
            //
            $geoController = new GeocoderController();
            $infoRequest = new Request();
            $lat = $location["latitude"] ?? $location["lat"];
            $lng = $location["longitude"] ?? $location["lng"] ?? $location["long"];
            $infoRequest->merge(["lat" => $lat, "lng" => $lng]);
            $coordinateInfo = $geoController->forward($infoRequest);
            //addresses array returned in an array with data key
            $addresses = $coordinateInfo->getData(true)["data"];
            //loop through to get the
            foreach ($addresses as $address) {
                $city = $city ?? $address["subLocality"] ?? $address["locality"];
                $state = $state ?? $address["administrative_area_level_1"] ?? $address["administrative_area_level_2"];
                $country = $country ?? $address["country"];
            }

            //update the location wih the missing info
            if ($isCityMissing) {
                $location["city"] = $city;
            }

            if ($isStateMissing) {
                $location["state"] = $state;
            }

            if ($isCountryMissing) {
                $location["country"] = $country;
            }
        }
        //
        return $location;
    }



    public function getDistanceBetweenStop($stopId, $nextStopId)
    {
        //drop-off location distance calculation
        $nextLocation = $this->getDeliveryAddress($nextStopId);
        return DeliveryAddress::distance($nextLocation->latitude, $nextLocation->longitude)
            ->where('id', $stopId)
            ->first()
            ->distance;
    }





    //MISC FUNCTION: CITY, STATE, COUNTRY Checks
    public function vendorCityLocationCheck($city, $country, $lat, $lng, $vendorId)
    {
        $found = false;
        $deliveryAddressCities = City::where('name', 'like', '%' . $city . '%')
            ->whereHas('state', function ($query) use ($country) {
                return $query->whereHas('country', function ($query) use ($country) {
                    return $query->where('name', "like", "%" . $country . "%");
                });
            })->get();
        //regular city loop
        foreach ($deliveryAddressCities as $deliveryAddressCity) {
            $pickupLocationCityVendor = CityVendor::where('vendor_id', $vendorId)
                ->where('city_id', $deliveryAddressCity->id)
                ->where('is_active', 1)
                ->first();

            if (!empty($pickupLocationCityVendor)) {
                $found = true;
                break;
            }
        }
        //check if location is in any city boundary
        if (!$found) {
            //get country where the location can be found
            $country = $this->inAnyCountryBoundary($lat, $lng);
            $state = $this->inAnyStateBoundary($lat, $lng, $country->id ?? null);
            $deliveryAddressCity = $this->inAnyCityBoundary($lat, $lng, $state->id ?? null);
            if ($deliveryAddressCity != null || !empty($deliveryAddressCity)) {
                $pickupLocationCityVendor = CityVendor::where('vendor_id', $vendorId)
                    ->where('city_id', $deliveryAddressCity->id)
                    ->where('is_active',  1)
                    ->first();
                $found = !empty($pickupLocationCityVendor);
            }
        }
        //
        return $found;
    }

    public function vendorStateLocationCheck($state, $country, $lat, $lng, $vendorId)
    {
        $found = false;
        $deliveryAddressStates = State::where('name', 'like', '%' . $state . '%')
            ->whereHas('country', function ($query) use ($country) {
                return $query->where('name', "like", "%" . $country . "%");
            })->get();
        //regular states loop
        foreach ($deliveryAddressStates as $deliveryAddressState) {
            $pickupLocationStateVendor = StateVendor::where('vendor_id', $vendorId)
                ->where('state_id', $deliveryAddressState->id)
                ->where('is_active', 1)
                ->first();

            if (!empty($pickupLocationStateVendor)) {
                $found = true;
                break;
            }
        }
        //check if location is in any state boundary
        if (!$found) {
            //get country where the location can be found
            $country = $this->inAnyCountryBoundary($lat, $lng);
            $deliveryAddressState = $this->inAnyStateBoundary($lat, $lng, $country->id ?? null);
            if ($deliveryAddressState != null || !empty($deliveryAddressState)) {
                $pickupLocationStateVendor = StateVendor::where('vendor_id', $vendorId)
                    ->where('state_id', $deliveryAddressState->id)
                    ->where('is_active', 1)
                    ->first();
                $found = !empty($pickupLocationStateVendor);
            }
        }

        //
        return $found;
    }

    public function vendorCountryLocationCheck($country, $lat, $lng, $vendorId)
    {
        $found = false;
        $deliveryAddressCountries = Country::where('name', 'like', '%' . $country . '%')->get();
        //regular country loop
        foreach ($deliveryAddressCountries as $deliveryAddressCountry) {
            $pickupLocationCountryVendor = CountryVendor::where('vendor_id', $vendorId)
                ->where('country_id', $deliveryAddressCountry->id)
                ->where('is_active', 1)
                ->first();

            if (!empty($pickupLocationCountryVendor)) {
                $found = true;
                break;
            }
        }
        //check if location is in any state boundary
        if (!$found) {
            //get country where the location can be found
            $deliveryAddressCountry = $this->inAnyCountryBoundary($lat, $lng);
            if ($deliveryAddressCountry != null || !empty($deliveryAddressCountry)) {
                $pickupLocationCountryVendor = CountryVendor::where('vendor_id', $vendorId)
                    ->where('country_id', $deliveryAddressCountry->id)
                    ->where('is_active', "=", 1)
                    ->first();
                if (!empty($pickupLocationCountryVendor)) {
                    return true;
                }
            }
        }

        //
        return $found;
    }
}