<?php

namespace App\Http\Livewire\Settings;


class Contact extends BaseSettingsComponent
{

    //
    public $contactInfo;
    //set listeners to emtpy
    protected $listeners = [];


    public function mount()
    {
        $this->contactSettings();
    }


    public function render()
    {
        return view('livewire.settings.contact');
    }



    //
    public function contactSettings()
    {
        $filePath = base_path() . "/resources/views/layouts/includes/contact.blade.php";
        $this->contactInfo = file_get_contents($filePath) ?? "";
    }


    public function setupData()
    {
        $this->emit('loadSummerNote', "contactInfoEdit", $this->contactInfo);
    }

    public function saveContactInfo()
    {

        try {

            $this->isDemo();
            $filePath = base_path() . "/resources/views/layouts/includes/contact.blade.php";
            file_put_contents($filePath, $this->contactInfo);

            $this->showSuccessAlert(__("Contact Info saved successfully!"));
            $this->setupData();
        } catch (\Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Contact Info save failed!"));
            $this->setupData();
        }
    }
}
