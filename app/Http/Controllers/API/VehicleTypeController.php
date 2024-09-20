<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TaxiCurrencyPricing;
use App\Models\VehicleType;
use App\Traits\GoogleMapApiTrait;
use App\Traits\TaxiTrait;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{

    use GoogleMapApiTrait, TaxiTrait;
    public function index()
    {
        return VehicleType::active()->get();
    }


    //using this to
    public function calculateFee(Request $request)
    {

        //
        $vehicleTypes = VehicleType::active()->inorder()->get();
        if (!$request->has('country_code') || $request->country_code == null || empty($request->country_code) || $request->country_code == "null") {
            try {
                $ip = $request->ip();
                $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
                $countryCode = $details->country ?? null;
                $request->merge(['country_code' => $countryCode]);
            } catch (\Exception $e) {
                $request->merge(['country_code' => null]);
            }
        }
        //check if multiple currency is enabled
        $multipleCurrency = (bool) setting('taxi.multipleCurrency', false);
        if ($multipleCurrency && $request->country_code) {
            //
            $taxiCurrencyVehicleTypes = TaxiCurrencyPricing::whereHas('currency', function ($query) use ($request) {
                return $query->where('country_code', $request->country_code);
            })->get();


            //
            $vehicleTypes = [];
            foreach ($taxiCurrencyVehicleTypes as $taxiCurrencyVehicleType) {
                $vehicleType = $taxiCurrencyVehicleType->vehicle_type;
                $vehicleType->base_fare = $taxiCurrencyVehicleType->base_fare;
                $vehicleType->distance_fare = $taxiCurrencyVehicleType->distance_fare;
                $vehicleType->time_fare = $taxiCurrencyVehicleType->time_fare;
                $vehicleType->min_fare = $taxiCurrencyVehicleType->min_fare;
                $vehicleType->currency = $taxiCurrencyVehicleType->currency;
                $vehicleType->total = $this->getTaxiOrderTotalPrice($vehicleType, $request->pickup, $request->dropoff);
                //add tax to the total
                $taxiTax = floatval(setting('taxi.tax', 0));
                $taxiTaxAmount = ($taxiTax / 100) * $vehicleType->total;
                $vehicleType->tax = $taxiTax;
                $vehicleType->total = $vehicleType->total + $taxiTaxAmount;
                //end taxi tax
                $vehicleType = $this->getFareBreakdown($vehicleType, $request->pickup, $request->dropoff);
                $vehicleType->encrypted = \Crypt::encrypt($vehicleType);
                $vehicleTypes[] = $vehicleType;
            }

            // return $vehicleTypes;
        }

        if (!$multipleCurrency || empty($vehicleTypes)) {
            //convert array to collection, if need be
            if (is_array($vehicleTypes)) {
                $vehicleTypes = collect($vehicleTypes);
            }
            //
            $vehicleTypes = $vehicleTypes->map(function ($vehicleType, $key) use ($request) {
                $amount = $this->getTaxiOrderTotalPrice($vehicleType, $request->pickup, $request->dropoff);
                $vehicleType->total = $amount;
                //add tax to the total
                $taxiTax = floatval(setting('taxi.tax', 0));
                $taxiTaxAmount = ($taxiTax / 100) * $vehicleType->total;
                $vehicleType->tax = $taxiTax;
                $vehicleType->total = $vehicleType->total + $taxiTaxAmount;
                //end taxi tax
                $vehicleType = $this->getFareBreakdown($vehicleType, $request->pickup, $request->dropoff);
                $vehicleType->encrypted = \Crypt::encrypt($vehicleType);
                return $vehicleType;
            });
        }
        return $vehicleTypes;
    }
}