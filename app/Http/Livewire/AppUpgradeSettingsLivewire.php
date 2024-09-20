<?php

namespace App\Http\Livewire;

use Exception;

class AppUpgradeSettingsLivewire extends BaseLivewireComponent
{

    // App settings
    public $customer = [];
    public $driver = [];
    public $vendor = [];



    public function mount()
    {
        //sms gateways 
        $this->customer = setting("upgrade.customer", [
            // "android" => "",
            // "ios" => "",
            "force" => "",
        ]);
        $this->driver = setting("upgrade.driver", [
            // "android" => "",
            // "ios" => "",
            "force" => "",
        ]);
        $this->vendor = setting("upgrade.vendor", [
            // "android" => "",
            // "ios" => "",
            "force" => "",
        ]);

        //
        $this->customer["force"] = (bool) setting("upgrade.customer.force");
        $this->driver["force"] = (bool) setting("upgrade.driver.force");
        $this->vendor["force"] = (bool) setting("upgrade.vendor.force");
    }

    public function render()
    {
        $this->mount();
        return view('livewire.settings.app-upgrade-settings');
    }


    public function save()
    {


        try {

            $this->isDemo();
            // update the site name
            setting([
                "upgrade" => [
                    "customer" => $this->customer,
                    "driver" => $this->driver,
                    "vendor" => $this->vendor,
                ]
            ])->save();

            $this->showSuccessAlert(__("App Settings saved successfully!"));
            $this->reset();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("App Settings save failed!"));
        }
    }
}
