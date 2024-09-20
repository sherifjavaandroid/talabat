<?php

namespace App\Http\Livewire\Settings;


class Terms extends BaseSettingsComponent
{

    //
    public $terms;
    //set listeners to emtpy
    protected $listeners = [];


    public function mount()
    {
        $this->termsSettings();
    }


    public function render()
    {
        return view('livewire.settings.terms');
    }



    //
    public function termsSettings()
    {
        $filePath = base_path() . "/resources/views/layouts/includes/terms.blade.php";
        $this->terms = file_get_contents($filePath) ?? "";
    }

    public function setupData()
    {
        $this->emit('loadSummerNote', "termsEdit", $this->terms);
    }


    public function saveTermsSettings()
    {

        try {

            $this->isDemo();
            $filePath = base_path() . "/resources/views/layouts/includes/terms.blade.php";
            file_put_contents($filePath, $this->terms);

            $this->showSuccessAlert(__("Terms & conditions saved successfully!"));
            $this->setupData();
        } catch (\Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Terms & conditions save failed!"));
            $this->setupData();
        }
    }
}
