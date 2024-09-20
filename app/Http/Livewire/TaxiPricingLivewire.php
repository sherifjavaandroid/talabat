<?php

namespace App\Http\Livewire;

use App\Models\Currency;
use App\Models\TaxiCurrencyPricing;
use App\Models\VehicleType;
use Illuminate\Support\Facades\DB;



class TaxiPricingLivewire extends BaseLivewireComponent
{

    public $model = TaxiCurrencyPricing::class;
    public $vehicleTypes;
    public $currencies;
    public $vehicle_type_id;
    public $currency_id;
    public $base_fare;
    public $distance_fare;
    public $time_fare;
    public $min_fare;
    public $is_active;

    protected $rules = [
        "base_fare" => "required|numeric",
        "distance_fare" => 'required|numeric',
        "time_fare" => 'required|numeric',
        "min_fare" => 'required|numeric',
        "is_active" => "sometimes",
    ];


    protected $messages = [
        "vehicle_type_id.exists" => "Invalid vendor selected",
        "currency_id.exists" => "Invalid category selected",
    ];


    public function mount(){
        
    }

    public function render()
    {
        $this->vehicleTypes = VehicleType::get();
        $this->currencies = Currency::get();
        return view('livewire.taxi.pricing');
    }


    public function save()
    {
        //validate
        $this->validate();

        try {
            $this->isDemo();
            DB::beginTransaction();
            $model = new TaxiCurrencyPricing();
            $model->vehicle_type_id = $this->vehicle_type_id ?? $this->vehicleTypes->first()->id ?? null;
            $model->currency_id = $this->currency_id ?? $this->currencies->first()->id ?? null;
            $model->base_fare = $this->base_fare;
            $model->distance_fare = $this->distance_fare;
            $model->time_fare = $this->time_fare;
            $model->min_fare = $this->min_fare;
            $model->is_active = $this->is_active;
            $model->save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Pricing") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Pricing") . " " . __('creation failed!'));
        }
    }


    public function initiateEdit($id)
    {
        $this->reset();
        $this->selectedModel = $this->model::find($id);
        $this->vehicle_type_id = $this->selectedModel->vehicle_type_id;
        $this->currency_id = $this->selectedModel->currency_id;
        $this->base_fare = $this->selectedModel->base_fare;
        $this->distance_fare = $this->selectedModel->distance_fare;
        $this->time_fare = $this->selectedModel->time_fare;
        $this->min_fare = $this->selectedModel->min_fare;
        $this->is_active = $this->selectedModel->is_active;
        $this->emit('showEditModal');
    }

    public function update()
    {

        //validate
        $this->validate([
            "base_fare" => "required|numeric",
            "distance_fare" => 'required|numeric',
            "time_fare" => 'required|numeric',
            "min_fare" => 'required|numeric',
        ]);

        try {
            $this->isDemo();
            DB::beginTransaction();
            $this->selectedModel->vehicle_type_id = $this->vehicle_type_id;
            $this->selectedModel->currency_id = $this->currency_id;
            $this->selectedModel->base_fare = $this->base_fare;
            $this->selectedModel->distance_fare = $this->distance_fare;
            $this->selectedModel->time_fare = $this->time_fare;
            $this->selectedModel->min_fare = $this->min_fare;
            $this->selectedModel->is_active = $this->is_active;
            $this->selectedModel->save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Pricing") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Pricing") . " " . __('update failed!'));
        }
    }
}
