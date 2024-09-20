<?php

namespace App\Http\Livewire;

use App\Traits\SystemUpdateTrait;
use Illuminate\Support\Facades\Http;

class UpgradeLivewire extends BaseLivewireComponent
{

    use SystemUpdateTrait;
    public $terminalCommand;
    public $terminalError;
    public $phone_code;
    public $verison_code;
    public $downloadableVersions = [];

    public $purchase_code;
    public $buyer_username;

    public function prepareData()
    {
        $this->purchase_code = setting('cc.purchase_code');
        $this->buyer_username = setting('cc.buyer_username');

        //select from settings table, where key = appVerisonCode using DB facade
        //if not found, set to 1
        //if found, set to value
        $versionValue = \DB::table('settings')->where('key', 'appVerisonCode')->first();
        if ($versionValue) {
            $this->verison_code = $versionValue->value;
        } else {
            $this->verison_code = 1;
        }
    }

    public function mount()
    {
        $this->prepareData();
    }

    public function render()
    {

        return view('livewire.settings.upgrade', [
            "verisonCodes" => $this->versionCodes(),
        ]);
    }

    public function versionCodes()
    {
        $appVersionCode = $this->verison_code;
        $verisons = range(1, $appVersionCode);
        return $verisons;
    }

    public function upgradeAppSystem()
    {

        try {
            //
            $this->isDemo();
            //
            if ($this->verison_code <= 15) {
                $this->runUpgradeAppSystemCommands();
            } else {
                //
                $newVerisonCode = $this->verison_code + 1;
                $upgradeClassName = "App\\Upgrades\\Upgrade" . $newVerisonCode . "";
                if (!class_exists($upgradeClassName)) {
                    $this->showSuccessAlert(__("System Updated Successfully!") . ". v" . $this->verison_code . "");
                    return;
                }
                $upgradeClass = new $upgradeClassName();
                $upgradeClass->run();
                $upgradeClass->update();
            }
            $this->showSuccessAlert(__("System Updated Successfully!"));
            $this->prepareData();
        } catch (\Exception $ex) {
            logger($ex);
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"), $time = 30000);
        }
    }



    public function rollBackUpgrade()
    {
        try {
            //
            $this->isDemo();
            setting([
                'appVerisonCode' =>  $this->verison_code,
            ])->save();
            $this->runUpgradeAppSystemCommands();
            $this->showSuccessAlert(__("Rollback Updated Successfully!"));
        } catch (\Exception $ex) {
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"));
        }
    }


    public function runTerminalCommand()
    {

        $this->terminalError = "";

        try {

            //
            $this->isDemo();
            $this->systemTerminalRun($this->terminalCommand);
            $this->showSuccessAlert(__("Terminal command successfully!"));
        } catch (\Exception $error) {
            $this->terminalError = $error->getMessage() ?? __("Terminal command failed!");
        }
    }


    //
    public function saveCodecanyon()
    {
        $this->validate([
            "purchase_code" => "required",
            "buyer_username" => "required",
        ]);

        setting([
            "cc" => [
                "purchase_code" => $this->purchase_code,
                "buyer_username" => $this->buyer_username,
            ]
        ])->save();
        $this->prepareData();
        $this->showSuccessAlert(__("Settings saved successfully!"));
    }


    public function getRemoteVersions()
    {
        $this->prepareData();
        //api call to get the links
        $response = Http::get('https://setup.edentech.online/api/app/versions', [
            "username" => $this->buyer_username,
            "code" => $this->purchase_code,
        ]);

        if ($response->successful()) {
            $this->downloadableVersions = $response->json();
        } else {
            $this->downloadableVersions = [];
            $this->showErrorAlert($response->json()['message'] ?? "Failed");
        }
        $this->showAssignModal();
    }
}
