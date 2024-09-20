<?php

namespace App\Http\Livewire\Settings;


class PaymentTerms extends BaseSettingsComponent
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
        return view('livewire.settings.payment-terms');
    }



    //
    public function termsSettings()
    {
        $filePath = base_path() . "/resources/views/layouts/includes/payment-terms.blade.php";
        $this->terms = file_get_contents($filePath) ?? "";
    }

    public function setupData()
    {
        $this->emit('loadSummerNote', "paymentTermsEdit", $this->terms);
    }


    public function saveTermsSettings()
    {

        try {

            $this->isDemo();
            $filePath = base_path() . "/resources/views/layouts/includes/payment-terms.blade.php";
            file_put_contents($filePath, $this->terms);

            $this->showSuccessAlert(__("Payment Terms & conditions saved successfully!"));
            $this->setupData();
        } catch (\Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Payment Terms & conditions save failed!"));
            $this->setupData();
        }
    }
}