<?php

namespace App\Http\Livewire\Settings;


class PrivacyPolicy extends BaseSettingsComponent
{

    //
    public $privacyPolicy;
    //set listeners to emtpy
    protected $listeners = [];


    public function mount()
    {
        $this->privacySettings();
    }


    public function render()
    {
        return view('livewire.settings.privacy-policy');
    }



    //Meeeting settings
    public function privacySettings()
    {

        $filePath = base_path() . "/resources/views/layouts/includes/privacy.blade.php";
        $this->privacyPolicy = file_get_contents($filePath) ?? "";
    }


    public function setupData()
    {
        $this->emit('loadSummerNote', "privacyPolicyEdit", $this->privacyPolicy);
    }

    public function savePrivacyPolicy()
    {

        try {

            $this->isDemo();
            $filePath = base_path() . "/resources/views/layouts/includes/privacy.blade.php";
            file_put_contents($filePath, $this->privacyPolicy);

            $this->showSuccessAlert(__("Privacy Policy Settings saved successfully!"));
            $this->setupData();
        } catch (\Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Privacy Policy Settings save failed!"));
            $this->setupData();
            // $this->showErrorAlert("Privacy Policy ===> " . $this->privacyPolicy . "");
        }
    }
}
