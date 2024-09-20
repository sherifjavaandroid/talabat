<?php

namespace App\Http\Livewire\Settings;

use Exception;
use Illuminate\Support\Facades\Storage;
use GeoSot\EnvEditor\Facades\EnvEditor;
use Illuminate\Support\Facades\DB;

class BackendSettings extends BaseSettingsComponent
{



    // App settings
    public $websiteName;
    public $countryCode;
    public $websiteColor;
    public $websiteLogo;
    public $oldWebsiteLogo;
    public $favicon;
    public $oldFavicon;
    public $loginImage;
    public $oldLoginImage;
    public $registerImage;
    public $oldRegisterImage;
    public $timeZone;

    public $locale;
    public $localeCode;
    public $languages = [];
    public $languageCodes = [];

    //
    public $backend_prefix;




    public function mount()
    {
        $this->loadLanguages();
        $this->appSettings();
    }





    public function render()
    {
        if (empty($this->languages)) {
            $this->loadLanguages();
        }
        return view('livewire.settings.backend-settings');
    }


    public function loadLanguages()
    {
        $this->languages = config('backend.languages', []);
        $this->languageCodes = config('backend.languageCodes', []);
    }


    //App settings
    public function appSettings()
    {
        $this->websiteName = setting('websiteName', env("APP_NAME"));
        $this->websiteColor = setting('websiteColor', '#21a179');
        $this->countryCode = setting('countryCode', "GH");
        $this->timeZone = setting('timeZone', "UTC");
        $this->oldWebsiteLogo = getValidValue(setting('websiteLogo'), asset('images/logo.png'));
        $this->oldFavicon = getValidValue(setting('favicon'), asset('images/logo.png'));
        $this->oldLoginImage = getValidValue(setting('loginImage'), asset('images/login.jpeg'));
        $this->oldRegisterImage = getValidValue(setting('registerImage'), asset('images/register.jpg'));
        $this->locale = setting('locale', 'en');
        $this->backend_prefix = env('BACKEND_ROUTE_PREFIX', '');
    }

    public function saveAppSettings()
    {

        $this->validate([
            "websiteLogo" => "sometimes|nullable|image|max:1024",
            "favicon" => "sometimes|nullable|image|mimes:png|max:48",
            "loginImage" => "sometimes|nullable|image|max:3072",
            "registerImage" => "sometimes|nullable|image|max:3072",
        ]);

        try {

            $this->isDemo();

            DB::beginTransaction();

            // store new logo
            if ($this->websiteLogo) {
                $this->oldWebsiteLogo = Storage::url($this->websiteLogo->store('public/logos'));
            }

            // store new logo
            if ($this->favicon) {
                $this->oldFavicon = Storage::url($this->favicon->store('public/favicons'));
            }

            // store new logo
            if ($this->loginImage) {
                $this->oldLoginImage = Storage::url($this->loginImage->store('public/auth/login'));
            }

            // store new logo
            if ($this->registerImage) {
                $this->oldRegisterImage = Storage::url($this->registerImage->store('public/auth/register'));
            }


            //
            EnvEditor::editKey("APP_NAME", "'" . $this->websiteName . "'");
            $selectedLanguageKey = array_search($this->locale, $this->languages);

            $appSettings = [
                'locale' =>  $this->locale,
                'localeCode' =>  $this->languageCodes[$selectedLanguageKey],
                'websiteName' =>  $this->websiteName,
                'websiteColor' =>  $this->websiteColor,
                'countryCode' =>  $this->countryCode,
                'timeZone' =>  $this->timeZone,
                'websiteLogo' =>  $this->oldWebsiteLogo,
                'favicon' =>  $this->oldFavicon,
                'loginImage' =>  $this->oldLoginImage,
                'registerImage' =>  $this->oldRegisterImage,
            ];

            // update the site name
            setting($appSettings)->save();
            // update the site route prefix
            setEnv("BACKEND_ROUTE_PREFIX", $this->backend_prefix);


            DB::commit();
            $this->showSuccessAlert(__("App Settings saved successfully!"));
            $this->goback();
        } catch (Exception $error) {
            DB::rollBack();
            $this->showErrorAlert($error->getMessage() ?? __("App Settings save failed!"));
        }
    }
}