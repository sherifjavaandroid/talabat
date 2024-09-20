<?php

namespace App\Http\Livewire;

use App\Models\Day;
use Illuminate\Support\Facades\DB;

class VendorTimingLivewire extends BaseLivewireComponent
{
    //
    public $showDayAssignment;
    public $days;
    public $workingDays;


    public function render()
    {
        return view('livewire.vendor_timing');
    }

    public function removeDay($index)
    {
        unset($this->workingDays[$index]);
    }

    public function addNewTiming()
    {
        $this->workingDays[] = [
            "day_id" => $this->days[0]->id,
            "open" => null,
            "close" => null,
        ];
    }

    //CUSTOM DAYS
    public function changeVendorTiming($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->days = Day::get();
        $vendorDays = $this->selectedModel->days;
        foreach ($vendorDays as $vendorDay) {
            $this->workingDays[] = [
                "day_id" => $vendorDay->pivot->day_id,
                "open" => $vendorDay->pivot->open,
                "close" => $vendorDay->pivot->close,
            ];
        }
        $this->showDayAssignment = true;
    }

    public function saveDays()
    {
        //
        try {

            $dayVendor = [];
            foreach ($this->workingDays as $key => $workingDay) {
                //
                $openTime = $this->workingDays[$key]["open"] ?? null;
                $closeTime = $this->workingDays[$key]["close"] ?? null;
                $this->resetValidation();

                if ($openTime == null && $closeTime == null) {
                    $this->addError('workingDays.' . $key . '.open', __('Both time must be supplied'));
                    $this->addError('workingDays.' . $key . '.close', __('Both time must be supplied'));
                    return;
                } else if ($openTime == null) {
                    $this->addError('workingDays.' . $key . '.open', __('Open time must be supplied'));
                    return;
                } else if ($closeTime == null) {
                    $this->addError('workingDays.' . $key . '.close', __('Close time must be supplied'));
                    return;
                } else if ($closeTime <= $openTime) {
                    $this->addError('workingDays.' . $key . '.close', __('Close time must be greater than open time'));
                    return;
                }

                //
                if ($openTime != null && $closeTime != null) {
                    array_push($dayVendor, [
                        "day_id" => $workingDay["day_id"] ?? $this->days[0]->id,
                        "vendor_id" => $this->selectedModel->id,
                        "open" => $openTime,
                        "close" => $closeTime,
                    ]);
                }
            }

            //
            $this->selectedModel->days()->detach();
            $this->selectedModel->days()->sync($dayVendor);
            $this->resetValidation();
            $this->emit('dismissModal');
            $this->showSuccessAlert(__("Vendor Open/close time") . " " . __("updated successfully!"));
        } catch (\Exception $error) {

            DB::rollback();
            $this->resetValidation();
            $this->showErrorAlert($error->getMessage() ?? __("Vendor Open/close time") . " " . __("update failed!"));
        }
    }
}
