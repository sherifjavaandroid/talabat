<?php

namespace App\Http\Livewire;


use App\Models\PaymentMethodVehicleType;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentMethodVehicleTypeLivewire extends BaseLivewireComponent
{

    public $model = PaymentMethodVehicleType::class;

    public $vehicle_type_id;
    public $payment_method_id;

    protected $rules = [
        "name" => "required|string",
        "base_fare" => "required|numeric",
        "distance_fare" => 'required|numeric',
        "time_fare" => 'required|numeric',
        "min_fare" => 'required|numeric',
        "photo" => "required|image|max:1024",
    ];

    public function render()
    {
        return view('livewire.taxi.payment_methods');
    }

    public function save()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $vehicleType = new VehicleType();
            $vehicleType->name = $this->name;
            $vehicleType->base_fare = $this->base_fare;
            $vehicleType->distance_fare = $this->distance_fare;
            $vehicleType->time_fare = $this->time_fare;
            $vehicleType->min_fare = $this->min_fare;
            $vehicleType->is_active = $this->is_active;
            $vehicleType->save();

            if ($this->photo) {
                
                $vehicleType->clearMediaCollection();
                $vehicleType->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Vehicle Type") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vehicle Type") . " " . __('creation failed!'));
        }
    }


    public function initiateEdit($id)
    {
        $this->reset();
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
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
        $this->validate();

        try {

            DB::beginTransaction();
            $this->selectedModel->name = $this->name;
            $this->selectedModel->base_fare = $this->base_fare;
            $this->selectedModel->distance_fare = $this->distance_fare;
            $this->selectedModel->time_fare = $this->time_fare;
            $this->selectedModel->min_fare = $this->min_fare;
            $this->selectedModel->is_active = $this->is_active;
            $this->selectedModel->save();

            if ($this->photo) {
                
                $this->selectedModel->clearMediaCollection();
                $this->selectedModel->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Vehicle Type") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vehicle Type") . " " . __('update failed!'));
        }
    }

}
