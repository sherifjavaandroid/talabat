<?php

namespace App\Http\Livewire\Settings;


class Page extends BaseSettingsComponent
{

    //
    public $driverDocumentInstructions;
    public $vendorDocumentInstructions;
    public $driverDocumentCount;
    public $vendorDocumentCount;



    public function mount()
    {
        $this->pageSettings();
    }


    public function render()
    {
        return view('livewire.settings.page');
    }



    //
    //PAGE SETTINGS
    public function pageSettings()
    {
        $this->driverDocumentInstructions = setting('page.settings.driverDocumentInstructions', "");
        $this->vendorDocumentInstructions = setting('page.settings.vendorDocumentInstructions', "");
        $this->driverDocumentCount = (int) setting('page.settings.driverDocumentCount', 3);
        $this->vendorDocumentCount = (int) setting('page.settings.vendorDocumentCount', 3);
    }

    public function savePageSettings()
    {

        try {

            $this->isDemo();

            setting([
                'page.settings.driverDocumentInstructions' =>  $this->driverDocumentInstructions,
                'page.settings.vendorDocumentInstructions' =>  $this->vendorDocumentInstructions,
                'page.settings.driverDocumentCount' =>  $this->driverDocumentCount,
                'page.settings.vendorDocumentCount' =>  $this->vendorDocumentCount,
            ])->save();

            $this->setupEditors();
            $this->showSuccessAlert(__("Page Settings saved successfully!"));
        } catch (\Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Page Settings save failed!"));
        }
    }


    public function setupEditors()
    {
        //
        $this->emit('loadSummerNote', "driverDocumentInstructionsEdit", $this->driverDocumentInstructions);
        $this->emit('loadSummerNote', "vendorDocumentInstructionsEdit", $this->vendorDocumentInstructions);
    }
}
