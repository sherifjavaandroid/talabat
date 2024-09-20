<?php

namespace App\Http\Livewire\Settings;


class CancelTerms extends BaseSettingsComponent
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
        return view('livewire.settings.cancel-terms');
    }



    //
    public function termsSettings()
    {
        $filePath = base_path() . "/resources/views/layouts/includes/cancel-terms.blade.php";
        // create the file if it does not exist
        if (!file_exists($filePath)) {
            file_put_contents($filePath, "");
        }
        $this->terms = file_get_contents($filePath) ?? "";
    }

    public function setupData()
    {
        $this->emit('loadSummerNote', "cancelTermsEdit", $this->terms);
    }


    public function saveTermsSettings()
    {

        try {

            $this->isDemo();
            $filePath = base_path() . "/resources/views/layouts/includes/cancel-terms.blade.php";
            file_put_contents($filePath, $this->terms);

            $this->showSuccessAlert(__("Saved successfully!"));
            $this->setupData();
        } catch (\Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Save failed!"));
            $this->setupData();
        }
    }
}