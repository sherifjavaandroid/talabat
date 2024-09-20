<?php

namespace App\Traits;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\CityVendor;
use App\Models\StateVendor;
use App\Models\CountryVendor;


trait PackageOrderTrait
{


    //misc.
    //for raw cuty, state and country
    public function isStopServiceByVendor($vendorId, $location)
    {

        $city = $location["city"];
        $state = $location["state"];
        $country = $location["country"];
        //check iof city is even in the system
        $deliveryAddressCity = City::where('name', 'like', '%' . $city . '%')
            ->whereHas('state', function ($query) use ($country) {
                return $query->whereHas('country', function ($query) use ($country) {
                    return $query->where('name', "like", "%" . $country . "%");
                });
            })
            ->first();


        if ($deliveryAddressCity != null || !empty($deliveryAddressCity)) {
            $pickupLocationCityVendor = CityVendor::where('vendor_id', $vendorId)
                ->where('city_id', $deliveryAddressCity->id)
                ->where('is_active', "=", 1)
                ->first();

            if (!empty($pickupLocationCityVendor)) {
                return true;
            }
        }


        //now check if delivery state is in the system
        $deliveryAddressState = State::where('name', 'like', '%' . $state . '%')
            ->whereHas('country', function ($query) use ($country) {
                return $query->where('name', "like", "%" . $country . "%");
            })
            ->first();

        if ($deliveryAddressState != null || !empty($deliveryAddressState)) {
            $pickupLocationStateVendor = StateVendor::where('vendor_id', $vendorId)
                ->where('state_id', $deliveryAddressState->id)
                ->where('is_active', "=", 1)
                ->first();
            if (!empty($pickupLocationStateVendor)) {
                return true;
            }
        }

        //now check if delivery country is in the system
        $deliveryAddressCountry = Country::where('name', 'like', '%' . $country . '%')->first();
        if ($deliveryAddressCountry != null || !empty($deliveryAddressCountry)) {
            $pickupLocationCountryVendor = CountryVendor::where('vendor_id', $vendorId)
                ->where('country_id', $deliveryAddressCountry->id)
                ->where('is_active', "=", 1)
                ->first();
            if (!empty($pickupLocationCountryVendor)) {
                return true;
            }
        }

        return false;
    }
}
