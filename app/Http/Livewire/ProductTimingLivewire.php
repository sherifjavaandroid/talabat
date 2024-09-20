<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Day;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductTimingLivewire extends BaseLivewireComponent
{

    //
    public $model = Product::class;
    //
    public $showDayAssignment;
    public $days;
    public $workingTimings;



    public function removeDay($index)
    {
        unset($this->workingTimings[$index]);
    }

    public function addNewTiming()
    {
        $this->workingTimings[] = [
            "id" => null,
            "day_id" => $this->days[0]->id,
            "start_time" => null,
            "end_time" => null,
        ];
    }


    //CUSTOM DAYS
    public function changeProductTiming($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->days = Day::get();
        $productTimings = $this->selectedModel->timings;
        foreach ($productTimings as $productTiming) {
            $this->workingTimings[] = [
                "id" => $productTiming->id,
                "day_id" => $productTiming->day_id,
                "start_time" => $productTiming->start_time,
                "end_time" => $productTiming->end_time,
            ];
        }
        $this->showDayAssignment = true;
    }

    public function saveDays()
    {
        //
        try {

            $dayProducts = [];
            $remIds = [];
            foreach ($this->workingTimings as $key => $workingDay) {
                //
                $openTime = $this->workingTimings[$key]["start_time"] ?? null;
                $closeTime = $this->workingTimings[$key]["end_time"] ?? null;
                $this->resetValidation();

                if ($openTime == null && $closeTime == null) {
                    $this->addError('workingTimings.' . $key . '.start_time', __('Both time must be supplied'));
                    $this->addError('workingTimings.' . $key . '.end_time', __('Both time must be supplied'));
                    return;
                } else if ($openTime == null) {
                    $this->addError('workingTimings.' . $key . '.start_time', __('Open time must be supplied'));
                    return;
                } else if ($closeTime == null) {
                    $this->addError('workingTimings.' . $key . '.end_time', __('Close time must be supplied'));
                    return;
                } else if ($closeTime <= $openTime) {
                    $this->addError('workingTimings.' . $key . '.end_time', __('Close time must be greater than open time'));
                    return;
                }

                //
                if ($openTime != null && $closeTime != null) {
                    //push to array
                    array_push($dayProducts, [
                        "id" => $workingDay["id"] ?? null,
                        "day_id" => $workingDay["day_id"] ?? $this->days[0]->id,
                        "product_id" => $this->selectedModel->id,
                        "start_time" => $openTime,
                        "end_time" => $closeTime,
                    ]);
                    //add id
                    if (isset($workingDay["id"])) {
                        array_push($remIds, $workingDay["id"]);
                    }
                }
            }

            //delete hasmany relation
            DB::beginTransaction();
            // $this->selectedModel->timings()->detach();
            // $this->selectedModel->timings()->sync($dayProduct);
            //delete all with id not in remIds
            $this->selectedModel->timings()->whereNotIn('id', $remIds)->delete();
            //create new or update
            foreach ($dayProducts as $dayProduct) {
                if (isset($dayProduct["id"])) {
                    $this->selectedModel->timings()->where('id', $dayProduct["id"])->update($dayProduct);
                } else {
                    $this->selectedModel->timings()->create($dayProduct);
                }
            }
            DB::commit();
            $this->resetValidation();
            $this->emit('dismissModal');
            $this->showSuccessAlert(__("Product Open/close time") . " " . __("updated successfully!"));
        } catch (Exception $error) {

            DB::rollback();
            $this->resetValidation();
            $this->showErrorAlert($error->getMessage() ?? __("Product Open/close time") . " " . __("update failed!"));
        }
    }
}
