<?php

namespace App\Http\Livewire\Component;

use App\Http\Controllers\API\GeocoderController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Livewire\Component;

class AutocompleteAddress extends Component
{

    public $title;
    public $name;
    public $addresses = [];
    public $address;
    public $extraData;

    protected $listeners = [
        'initialAddressSelected' => 'initialAddressSelected',
        'addressSelected' => 'addressSelected',
    ];



    public function render()
    {
        return view('livewire.component.autocomplete-address');
    }

    public function updatedAddress()
    {
        if (!empty($this->address)) {
            $this->fetchPlaces();
        }
    }
    //call rightful api geoservice
    private function fetchPlaces()
    {
        $this->addresses = [];
        $geocoderController = new GeocoderController();
        $request = new Request();
        $request->replace([
            'keyword' => $this->address,
            'locoation' => null,
        ]);
        $addresses = $geocoderController->reverse($request)->getData()->data;
        $addresses = json_decode(json_encode($addresses), true);
        foreach ($addresses as $prediction) {
            array_push($this->addresses, [
                "name" => $prediction["description"] ?? $prediction['formatted_address'] ?? "",
                "id" => $prediction["place_id"] ?? null,
                "address" => $prediction['formatted_address'],
                "latitude" => $prediction["geometry"]["location"]["lat"],
                "longitude" => $prediction["geometry"]["location"]["lng"],
                "city" => $prediction['subLocality'] ?? $prediction['locality'] ?? "",
                "state" => $prediction['administrative_area_level_1'] ?? $prediction['administrative_area_level_2'] ?? "",
                "country" => $prediction['country'] ?? "",
            ]);
        }


        //emit raw data enatered by user
        if (empty($this->addresses)) {
            $fullAddressData = [
                "address" => $this->address,
                "latitude" =>  0.00,
                "longitude" => 0.00,
                "city" => "",
                "state" => "",
                "country" => "",
            ];

            $this->emitUp('autocompleteAddressSelected', $fullAddressData);
        }
    }

    public function initialAddressSelected($address)
    {

        $this->address = $address;
    }

    public function addressSelected($selectedIndex)
    {

        logger("addressSelected:", [
            "selectedIndex" => $selectedIndex,
        ]);

        if (!array_key_exists($selectedIndex, $this->addresses)) {
            return;
        }

        //if id is null
        if ($this->addresses[$selectedIndex]["id"] == null) {
            $mAddress = $this->addresses[$selectedIndex];
            $this->address = $mAddress['address'];
            $fullAddressData = [
                "address" => $mAddress['address'],
                "latitude" => $mAddress['latitude'],
                "longitude" => $mAddress['longitude'],
                "city" => $mAddress['city'],
                "state" => $mAddress['state'],
                "country" => $mAddress['country'],
            ];

            $this->emitUp('autocompleteAddressSelected', $fullAddressData, $this->extraData);
            $this->addresses = [];
            return;
        }


        //only for google useage
        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
            "placeid" => $this->addresses[$selectedIndex]["id"],
            "key" => env('googleMapKey'),
        ]);
        $this->addresses = [];

        if ($response->successful()) {


            $city = "";
            $state = "";
            $country = "";

            $addressComponents = $response->json()["result"]["address_components"];
            //
            foreach ($addressComponents as $key => $addressComponent) {
                //country
                if (in_array("country", $addressComponent["types"])) {
                    $country = $addressComponent["long_name"];
                }
                //state
                else if (in_array("administrative_area_level_1", $addressComponent["types"])) {
                    $state = $addressComponent["long_name"];
                }
                //city
                else if (in_array("locality", $addressComponent["types"])) {
                    $city = $addressComponent["long_name"];
                }
            }

            $this->address = $response->json()["result"]["formatted_address"];
            $fullAddressData = [
                "address" => $this->address,
                "latitude" => $response->json()["result"]["geometry"]["location"]["lat"],
                "longitude" => $response->json()["result"]["geometry"]["location"]["lng"],
                "city" => $city,
                "state" => $state,
                "country" => $country,
            ];

            $this->emitUp('autocompleteAddressSelected', $fullAddressData, $this->extraData);
        } else {
            // emit error to view
        }
    }
}
