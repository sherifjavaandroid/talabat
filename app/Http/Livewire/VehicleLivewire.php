<?php

namespace App\Http\Livewire;

use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Support\Facades\DB;
use Exception;

class VehicleLivewire extends BaseLivewireComponent
{

    public $model = Vehicle::class;
    public $vehicleTypes;
    public $carModelSearchClause = [];
    public $car_model_id;
    public $driver_id;
    public $vehicle_type_id;
    public $reg_no;
    public $color;
    public $is_active;
    public $photos;

    protected $rules = [
        "car_model_id" => "required|exists:car_models,id",
        "driver_id" => "required|exists:users,id",
        "vehicle_type_id" => "required|exists:vehicle_types,id",
        "reg_no" => "required|string",
        "color" => "required|string",
    ];

    public function render()
    {

        $this->vehicleTypes = VehicleType::active()->get();
        return view('livewire.taxi.vehicles');
    }


    //use to handle car make selected
    public function autocompleteCategorySelected($carMake)
    {
        try {
            $this->carModelSearchClause = ['car_make_id' => $carMake["id"]];
            $this->emit('carModelQueryClasueUpdate', $this->carModelSearchClause);
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }

    //use to handle car model selected
    public function autocompleteAddressSelected($carModel)
    {
        try {
            $this->car_model_id = $carModel["id"];
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }

    public function autocompleteDriverSelected($driver)
    {
        try {
            $this->driver_id = $driver["id"];
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }

    public function photoSelected($photos)
    {
        $this->photos = $photos;
    }

    public function save()
    {

        //
        $this->vehicle_type_id = $this->vehicle_type_id ??  $this->vehicleTypes->first()->id;
        //validate
        $this->validate();

        try {
            DB::beginTransaction();
            $model = new Vehicle();
            $model->car_model_id = $this->car_model_id;
            $model->driver_id = $this->driver_id;
            $model->vehicle_type_id = $this->vehicle_type_id ?? $this->vehicleTypes->first()->id;
            $model->reg_no = $this->reg_no;
            $model->color = $this->color;
            $model->is_active = $this->is_active;
            $model->save();

            if ($this->photos) {

                $model->clearMediaCollection();
                foreach ($this->photos as $photo) {
                    $model->addMedia($photo)->toMediaCollection();
                }
                $this->photos = null;
            }
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Vehicle") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            logger("error", [$error]);
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vehicle") . " " . __('creation failed!'));
        }
    }


    public function initiateEdit($id)
    {
        $this->reset();
        $this->selectedModel = $this->model::find($id);
        $this->car_model_id = $this->selectedModel->car_model_id;
        $this->driver_id = $this->selectedModel->driver_id;
        $this->vehicle_type_id = $this->selectedModel->vehicle_type_id;
        $this->reg_no = $this->selectedModel->reg_no;
        $this->color = $this->selectedModel->color;
        $this->is_active = $this->selectedModel->is_active;

        //

        $this->emit('preselectedDeliveryBoyEmit', \Str::ucfirst($this->selectedModel->driver->name ?? ''));
        $this->emit('preselectedCarMakeEmit', \Str::ucfirst($this->selectedModel->car_model->car_make->name ?? ''));
        $this->emit('preselectedCarModelEmit', \Str::ucfirst($this->selectedModel->car_model->name ?? ''));
        $this->emit('vehiclePreviewsListener', $this->selectedModel->photos ?? []);
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate();

        try {
            $this->isDemo();
            DB::beginTransaction();
            $this->selectedModel->car_model_id = $this->car_model_id;
            $this->selectedModel->driver_id = $this->driver_id;
            $this->selectedModel->vehicle_type_id = $this->vehicle_type_id;
            $this->selectedModel->reg_no = $this->reg_no;
            $this->selectedModel->color = $this->color;
            $this->selectedModel->is_active = $this->is_active;
            $this->selectedModel->save();

            if ($this->photos) {

                $this->selectedModel->clearMediaCollection();
                foreach ($this->photos as $photo) {
                    $this->selectedModel->addMedia($photo)->toMediaCollection();
                }
                $this->photos = null;
            }
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Vehicle") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vehicle") . " " . __('update failed!'));
        }
    }

    public function verifyVehicle()
    {
        try {
            DB::beginTransaction();
            $this->selectedModel->is_active = true;
            $this->selectedModel->verified = true;
            $this->selectedModel->save();

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Vehicle") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vehicle") . " " . __('update failed!'));
        }
    }
}
