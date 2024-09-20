<?php

namespace App\Services;


use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class GeoBoundaryService
{

    //fix countries boundaries
    static public function syncCountriesBoundaries()
    {

        // ini_set('max_execution_time', 100);
        $query =  Country::whereNull('boundaries');
        $total = $query->count();
        $synced = 0;
        $query->chunk(40, function ($countries) use ($synced) {
            foreach ($countries as $country) {
                try {
                    $countryName = $country->name;
                    $country->boundaries = self::getPlaceBoundaries($countryName, "country");
                    $country->save();
                    $synced++;
                } catch (\Exception $e) {
                    $country->delete();
                    logger("country boundaries error", [$e->getMessage()]);
                }
            }
        });

        //
        if ($total > $synced) {
            $msg = __("Failed to sync all boundaries!");
            $msg .= " ";
            $msg .= __(" :synced out of :total synced!", ['synced' => $synced, 'total' => $total]);
            throw new \Exception($msg);
        }
    }
    //fix states boundaries
    static public function syncStatesBoundaries()
    {
        // ini_set('max_execution_time', 240);
        $query = State::whereNull('boundaries')
            ->whereHas('country', function ($query) {
                $query->whereNotNull('boundaries');
            });
        $total = $query->count();
        $synced = 0;
        $query->chunk(100, function ($states) use ($synced) {
            foreach ($states as $state) {
                try {
                    $stateName = $state->name;
                    $countryName = $state->country->name;
                    $keyword = $stateName . ", " . $countryName;
                    $state->boundaries = self::getPlaceBoundaries($keyword, "state");
                    $state->save();
                    $synced++;
                } catch (\Exception $e) {
                    $state->delete();
                    logger("state boundaries error", [$e->getMessage()]);
                }
            }
        });

        //
        if ($total > $synced) {
            $msg = __("Failed to sync all boundaries!");
            $msg .= " ";
            $msg .= __(" :synced out of :total synced!", ['synced' => $synced, 'total' => $total]);
            throw new \Exception($msg);
        }
    }
    //fix cities boundaries
    static public function syncCitiesBoundaries()
    {
        // ini_set('max_execution_time', 360);
        $query = City::whereNull('boundaries')
            ->whereHas('state', function ($query) {
                $query->whereNotNull('boundaries');
            });
        $total = $query->count();
        $synced = 0;
        $query->chunk(500, function ($cities) use ($synced) {
            foreach ($cities as $city) {
                try {

                    $cityName = $city->name;
                    $stateName = $city->state->name;
                    $countryName = $city->state->country->name;
                    $keyword = $cityName . ", " . $stateName . ", " . $countryName;
                    $city->boundaries = self::getPlaceBoundaries($keyword, "city");
                    $city->save();
                    $synced++;
                } catch (\Exception $e) {
                    $city->delete();
                    logger("city boundaries error", [$e->getMessage()]);
                }
            }
        });

        //
        if ($total > $synced) {
            $msg = __("Failed to sync all boundaries!");
            $msg .= " ";
            $msg .= __(" :synced out of :total synced!", ['synced' => $synced, 'total' => $total]);
            throw new \Exception($msg);
        }
    }





    //
    static public function getPlaceBoundaries($keyword, $type = "state")
    {
        //get place info first
        $apiKey = env('GEOAPIFY_API_KEY', "1ece41ee0c054fc08bb610fdadbb8bc4");
        // $keyword = $stateName . ", " . $countryName;
        $url = "https://api.geoapify.com/v1/geocode/search?text=$keyword&limit=2&type=$type&format=json&apiKey=$apiKey";
        // logger("place search url", [$url]);
        $response = Http::get($url);
        $placeId = "";
        if ($response->successful()) {
            // logger("place search response", [$response->json()]);
            $places = $response->json()["results"] ?? [];
            if (count($places) > 0) {
                //loop through the places,
                //find the one with highest confidence
                $currentConfidence = 0;
                foreach ($places as $place) {
                    // logger("Place Info", [$place]);
                    $placeConfidence = $place['rank']["confidence"] ?? 0.5;
                    if ($placeConfidence > $currentConfidence) {
                        $currentConfidence = $placeConfidence;
                        $placeId = $place['place_id'];
                    }
                }
            } else {
                throw new \Exception("$keyword could not be decoded!");
            }
        } else {
            throw new \Exception("$keyword boundaries not found!");
        }

        //if placeId is found
        if (empty($placeId)) {
            throw new \Exception("$keyword info not found!");
        }


        //fetch the place details
        $url = "https://api.geoapify.com/v2/place-details?id=$placeId&apiKey=$apiKey";
        // logger("place details url", [$url]);
        $response = Http::get($url);
        if ($response->successful()) {
            // logger("place response", [$response->json()]);
            $place = $response->json();
            $boundaries = $place['features'][0]['geometry']['coordinates'][0];
            // logger("place coordinates", [$place['features'][0]['geometry']['coordinates'][0]]);
        } else {
            throw new \Exception("$keyword details fetch failed. Double check the name!");
        }

        return $boundaries ?? [];
    }
}