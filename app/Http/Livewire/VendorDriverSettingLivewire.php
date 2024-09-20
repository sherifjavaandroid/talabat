<?php

namespace App\Http\Livewire;

use App\Models\VendorSetting;
use Exception;
use Illuminate\Support\Facades\DB;

class VendorDriverSettingLivewire extends BaseLivewireComponent
{

    //
    public $driverSearchRadius;
    public $maxDriverOrderAtOnce;
    public $maxDriverOrderNotificationAtOnce;
    public $vendorSettings;

    protected $rules = [
        "driverSearchRadius" => "required|numeric",
        "maxDriverOrderAtOnce" => "required|numeric",
        "maxDriverOrderNotificationAtOnce" => "required|numeric",
    ];

    public function mount()
    {
        $this->vendorSettings = VendorSetting::firstOrNew(
            ['vendor_id' => \Auth::user()->vendor_id],
            [
                'settings' => json_encode([
                    'driver_search_radius' => setting('driverSearchRadius', 10),
                    'max_driver_order_at_once' => setting('maxDriverOrderAtOnce', 10),
                    'max_driver_order_notification_at_once' => setting('maxDriverOrderNotificationAtOnce', 10),
                ])
            ],
        );
        $settings = json_decode($this->vendorSettings->settings, true) ?? [];
        //
        $this->driverSearchRadius = $settings['driver_search_radius'];
        $this->maxDriverOrderAtOnce = $settings['max_driver_order_at_once'];
        $this->maxDriverOrderNotificationAtOnce = $settings['max_driver_order_notification_at_once'];
    }


    public function render()
    {
        return view('livewire.vendor_driver_settings');
    }

    public function saveDriverSettings()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $vendorSetting = VendorSetting::updateOrCreate(
                ['vendor_id' => \Auth::user()->vendor_id],
                [
                    'settings' => json_encode([
                        'driver_search_radius' => $this->driverSearchRadius,
                        'max_driver_order_at_once' => $this->maxDriverOrderAtOnce,
                        'max_driver_order_notification_at_once' => $this->maxDriverOrderNotificationAtOnce,
                    ])
                ],
            );

            DB::commit();
            $this->dismissModal();
            $this->mount();
            $this->showSuccessAlert(__("Vendor Driver Settings") . " " . __('saved successfully!'));
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vendor Driver Settings") . " " . __('failed!'));
        }
    }
}
