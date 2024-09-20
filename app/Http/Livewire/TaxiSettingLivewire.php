<?php

namespace App\Http\Livewire;

use Exception;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\CommonMarkConverter;
use GeoSot\EnvEditor\Facades\EnvEditor;

class TaxiSettingLivewire extends BaseLivewireComponent
{

    // App settings
    public $cancelPendingTaxiOrderTime;
    public $drivingSpeed;
    public $taxiMaxScheduleDays;
    public $pending;
    public $preparing;
    public $ready;
    public $enroute;
    public $completed;
    public $cancelled;
    public $failed;

    public $cash_overdraft_completed;
    public $overdraft_completed;

    public $multipleCurrency;
    public $canScheduleTaxiOrder;
    public $taxiUseFirebaseServer;
    public $delayTaxiMatching;
    public $delayResearchTaxiMatching;
    public $requestBookingCode;
    public $bookingCodeOptions = ["none", "before", "after"];
    public $recalculateFare;
    public $showTaxiPickupInfo;
    public $showTaxiDropoffInfo;
    //
    public $tax;



    //rules
    protected function rules()
    {
        return [
            'drivingSpeed' => 'required|numeric|min:1',
            'taxiMaxScheduleDays' => 'required|numeric|min:1',
            'tax' => 'required|numeric|min:0|max:100',
        ];
    }

    public function mount()
    {
        $this->cancelPendingTaxiOrderTime = setting('taxi.cancelPendingTaxiOrderTime', 2);
        $this->drivingSpeed = setting('taxi.drivingSpeed', 50);
        $this->taxiMaxScheduleDays = setting('taxi.taxiMaxScheduleDays', 3);
        $this->pending = setting('taxi.msg.pending', "");
        $this->preparing = setting('taxi.msg.preparing', "");
        $this->ready = setting('taxi.msg.ready', "");
        $this->enroute = setting('taxi.msg.enroute', "");
        $this->completed = setting('taxi.msg.completed', "");
        $this->cancelled = setting('taxi.msg.cancelled', "");
        $this->failed = setting('taxi.msg.failed', "");
        //
        $this->cash_overdraft_completed = setting('taxi.msg.cash_overdraft_completed', "");
        $this->overdraft_completed = setting('taxi.msg.overdraft_completed', "");
        //
        $this->multipleCurrency = (bool) setting('taxi.multipleCurrency', false);
        $this->canScheduleTaxiOrder = (bool) setting('taxi.canScheduleTaxiOrder', false);
        $this->taxiUseFirebaseServer = (bool) setting('taxiUseFirebaseServer');
        $this->delayTaxiMatching = setting('taxiDelayTaxiMatching', 2);
        $this->delayResearchTaxiMatching = setting('delayResearchTaxiMatching', 30);
        $this->requestBookingCode = setting('taxi.requestBookingCode');
        $this->recalculateFare = (bool) setting('taxi.recalculateFare', false);
        //
        $this->showTaxiPickupInfo = (bool) setting('taxi.showTaxiPickupInfo', true);
        $this->showTaxiDropoffInfo = (bool) setting('taxi.showTaxiDropoffInfo', true);
        //
        $this->tax = setting('taxi.tax', 0);
    }

    public function render()
    {
        return view('livewire.taxi.taxi_settings');
    }




    public function saveSettings()
    {

        $this->validate();
        try {

            $alertDuration = setting('alertDuration', 5);
            if (!empty($this->delayResearchTaxiMatching) && $this->delayResearchTaxiMatching < ($alertDuration * 2)) {
                $this->addError('delayResearchTaxiMatching', __("Delay same order subsequent Search must be at least twice the alert duration!"));
                $this->showErrorAlert(__("Delay same order subsequent Search must be at least twice the alert duration!"));
                return;
            }


            $this->isDemo();

            $appSettings = [
                'taxi.showTaxiPickupInfo' =>  (bool) $this->showTaxiPickupInfo,
                'taxi.showTaxiDropoffInfo' =>  (bool) $this->showTaxiDropoffInfo,
                //
                'taxi.cancelPendingTaxiOrderTime' =>  $this->cancelPendingTaxiOrderTime,
                'taxi.drivingSpeed' =>  $this->drivingSpeed,
                'taxi.msg.pending' =>  $this->pending,
                'taxi.msg.preparing' =>  $this->preparing,
                'taxi.msg.ready' =>  $this->ready,
                'taxi.msg.enroute' =>  $this->enroute,
                'taxi.msg.completed' =>  $this->completed,
                'taxi.msg.cancelled' =>  $this->cancelled,
                'taxi.msg.failed' =>  $this->failed,

                'taxi.msg.cash_overdraft_completed' =>  $this->cash_overdraft_completed,
                'taxi.msg.overdraft_completed' =>  $this->overdraft_completed,

                'taxi.recalculateFare' =>  $this->recalculateFare,
                'taxi.multipleCurrency' =>  $this->multipleCurrency,
                'taxi.canScheduleTaxiOrder' =>  $this->canScheduleTaxiOrder,
                'taxi.taxiMaxScheduleDays' =>  $this->taxiMaxScheduleDays,
                'taxi.requestBookingCode' =>  $this->requestBookingCode,
                'taxiUseFirebaseServer' =>  $this->taxiUseFirebaseServer,
                'taxiDelayTaxiMatching' =>  $this->delayTaxiMatching,
                'delayResearchTaxiMatching' =>  $this->delayResearchTaxiMatching,

                //
                'taxi.tax' =>  $this->tax,
            ];

            // update the site name
            setting($appSettings)->save();

            //
            if ($this->recalculateFare) {
                //auto enable allow wallet once the recalculateFare is enabled
                setting([
                    "finance.allowWallet" => $this->recalculateFare
                ])->save();
            }



            $this->showSuccessAlert(__("App Settings saved successfully!"));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("App Settings save failed!"));
        }
    }
}