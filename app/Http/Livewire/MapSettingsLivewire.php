<?php

namespace App\Http\Livewire;

use Exception;
use GeoSot\EnvEditor\Facades\EnvEditor;

class MapSettingsLivewire extends BaseLivewireComponent
{

    // App settings
    public $useGoogleOnApp = true;
    public $googleMapKey;
    public $what3wordsApiKey;
    public $opencageApiKey;
    public $radarApiKey;
    public $locationiqApiKey;
    public $placeFilterCountryCodes;
    public $geocoders = ["Google", "Opencage", "Radar", "Locationiq"];
    public $geocoderType;
    //
    public $geoapifyApiKey;


    public function mount()
    {

        //
        if (!\App::environment('production')) {
            $this->googleMapKey = "XXXXXXXXXXXX";
            $this->what3wordsApiKey = "XXXXXXXXXXXX";
            $this->opencageApiKey = "XXXXXXXXXXXX";
            $this->radarApiKey = "XXXXXXXXXXXX";
            $this->locationiqApiKey = "XXXXXXXXXXXX";
            $this->geoapifyApiKey = "XXXXXXXXXXXX";
        } else {
            $this->googleMapKey = $this->getEnvKey("googleMapKey");
            $this->what3wordsApiKey = $this->getEnvKey('what3wordsApiKey');
            $this->opencageApiKey = $this->getEnvKey('opencageApiKey');
            $this->radarApiKey = $this->getEnvKey('radarApiKey');
            $this->locationiqApiKey = $this->getEnvKey('locationiqApiKey');
            $this->geoapifyApiKey = $this->getEnvKey('GEOAPIFY_API_KEY');
        }

        //
        $this->geocoderType = $this->getEnvKey('geocoderType');
        $this->useGoogleOnApp = $this->getEnvKey('useGoogleOnApp');
        $this->placeFilterCountryCodes = $this->getEnvKey('placeFilterCountryCodes');
    }

    public function render()
    {

        $this->mount();
        return view('livewire.settings.map-settings');
    }


    public function saveAppSettings()
    {

        $this->validate([
            "googleMapKey" => "sometimes|nullable|string",
            "what3wordsApiKey" => "sometimes|nullable|string",
            "opencageApiKey" => "sometimes|nullable|string",
            "radarApiKey" => "sometimes|nullable|string",
            "locationiqApiKey" => "sometimes|nullable|string",
            "geocoderType" => "required",
        ]);

        try {

            $this->isDemo();
            $appSettings = [
                'googleMapKey' => ($this->googleMapKey == "XXXXXXXXXXXX") ? setting('googleMapKey', 'XXXXXXXXXXXX') : $this->googleMapKey ?? "",
                'what3wordsApiKey' =>  $this->what3wordsApiKey ?? "",
                "map" => [
                    'opencageApiKey' =>  $this->opencageApiKey ?? "",
                    'radarApiKey' =>  $this->radarApiKey ?? "",
                    'locationiqApiKey' =>  $this->locationiqApiKey ?? "",
                    'geocoderType' =>  $this->geocoderType ?? "",
                    'useGoogleOnApp' =>  $this->useGoogleOnApp ?? "",
                    'placeFilterCountryCodes' =>  $this->placeFilterCountryCodes ?? "",
                ],
            ];

            // update the site name
            setting($appSettings)->save();

            //
            $this->setEnvKey(
                "googleMapKey",
                ($this->googleMapKey == "XXXXXXXXXXXX") ? setting('googleMapKey', 'XXXXXXXXXXXX') : $this->googleMapKey,
            );
            $this->setEnvKey('what3wordsApiKey',  $this->what3wordsApiKey,);
            $this->setEnvKey('opencageApiKey',  $this->opencageApiKey,);
            $this->setEnvKey('radarApiKey',  $this->radarApiKey,);
            $this->setEnvKey('locationiqApiKey',  $this->locationiqApiKey,);
            $this->setEnvKey('geocoderType',  $this->geocoderType,);
            $this->setEnvKey('useGoogleOnApp',  $this->useGoogleOnApp,);
            $this->setEnvKey('placeFilterCountryCodes',  $this->placeFilterCountryCodes,);
            //
            $this->setEnvKey('GEOAPIFY_API_KEY',  $this->geoapifyApiKey);





            $this->showSuccessAlert(__("Map Settings saved successfully!"));
            $this->reset();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Map Settings save failed!"));
        }
    }



    public function setEnvKey($key, $value)
    {
        if (EnvEditor::keyExists($key)) {
            EnvEditor::editKey($key, $value);
        } else {
            EnvEditor::addKey($key, $value);
        }
    }

    public function getEnvKey($key)
    {
        return env($key);
    }
}
