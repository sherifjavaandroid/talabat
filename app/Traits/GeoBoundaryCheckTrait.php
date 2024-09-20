<?php

namespace App\Traits;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\DeliveryZonePoint;

trait GeoBoundaryCheckTrait
{
    use GoogleMapApiTrait;

    public function inAnyCityBoundary($lat, $lng, $stateId = null): ?City
    {
        $cLatLng = [
            'lat' => $lat,
            'lng' => $lng
        ];
        //get all Cities but in chunks of 500
        City::when($stateId, function ($query) use ($stateId) {
            return $query->where('state_id', $stateId);
        })
            ->whereNotNull('boundaries')
            ->chunk(50, function ($cities) use ($cLatLng) {
                foreach ($cities as $city) {
                    $isMultiPolygon = $this->isMultiPolygon($city->boundaries);
                    if ($isMultiPolygon) {
                        $polygonPoints = $this->getMultiPolygon($city->boundaries);
                        foreach ($polygonPoints as $polygonPoint) {
                            if ($this->insideBound($cLatLng, $polygonPoint)) {
                                return $city;
                            }
                        }
                    } else {
                        $points = $this->formatBoundaries($city->boundaries);
                        if ($this->insideBound($cLatLng, $points)) {
                            return $city;
                        }
                    }
                }
            });
        return null;
    }
    public function inAnyStateBoundary($lat, $lng, $countryId = null): ?State
    {

        $cLatLng = [
            'lat' => $lat,
            'lng' => $lng
        ];
        //get all States but in chunks of 100
        State::when($countryId, function ($query) use ($countryId) {
            return $query->where('country_id', $countryId);
        })
            ->whereNotNull('boundaries')
            ->chunk(50, function ($states) use ($cLatLng) {
                foreach ($states as $state) {
                    $isMultiPolygon = $this->isMultiPolygon($state->boundaries);
                    if ($isMultiPolygon) {
                        $polygonPoints = $this->getMultiPolygon($state->boundaries);
                        foreach ($polygonPoints as $polygonPoint) {
                            if ($this->insideBound($cLatLng, $polygonPoint)) {
                                return $state;
                            }
                        }
                    } else {
                        $points = $this->formatBoundaries($state->boundaries);
                        if ($this->insideBound($cLatLng, $points)) {
                            return $state;
                        }
                    }
                }
            });
        return null;
    }
    public function inAnyCountryBoundary($lat, $lng): ?Country
    {
        $cLatLng = [
            'lat' => $lat,
            'lng' => $lng
        ];
        //get all countries but in chunks of 50
        Country::whereNotNull('boundaries')
            ->chunk(50, function ($countries) use ($cLatLng) {
                foreach ($countries as $country) {
                    $isMultiPolygon = $this->isMultiPolygon($country->boundaries);
                    if ($isMultiPolygon) {
                        $polygonPoints = $this->getMultiPolygon($country->boundaries);
                        foreach ($polygonPoints as $polygonPoint) {
                            if ($this->insideBound($cLatLng, $polygonPoint)) {
                                return $country;
                            }
                        }
                    } else {
                        $points = $this->formatBoundaries($country->boundaries);
                        if ($this->insideBound($cLatLng, $points)) {
                            //how to return the country to the calling function
                            return $country;
                        }
                    }
                }
            });
        return null;
    }


    //misc
    public function formatBoundaries($boundaries)
    {
        $boundaries = json_decode($boundaries);
        $boundaries = collect($boundaries)->map(function ($boundary) {
            $point = new DeliveryZonePoint();
            $point->lat = $boundary->lat ?? $boundary[0];
            $point->lng = $boundary->lng ?? $boundary[1];
            return $point;
        });
        return $boundaries;
    }

    public function isMultiPolygon($boundaries)
    {
        $boundaries = json_decode($boundaries);
        //check if the first element is an array
        return is_array($boundaries[0]);
    }

    public function getMultiPolygon($boundaries)
    {
        $boundaries = json_decode($boundaries);
        $multiPolygon = [];
        foreach ($boundaries as $boundary) {
            $boundary = json_encode($boundary);
            $multiPolygon[] = $this->formatBoundaries($boundary);
        }
        return $multiPolygon;
    }
}