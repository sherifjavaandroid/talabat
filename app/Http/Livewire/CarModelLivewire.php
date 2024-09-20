<?php

namespace App\Http\Livewire;

use App\Models\CarModel;
use Illuminate\Support\Facades\DB;
use Exception;

class CarModelLivewire extends BaseLivewireComponent
{

    public $model = CarModel::class;

    public $name;
    public $car_make_id;

    protected $rules = [
        "name" => "required|string",
    ];

    public function render()
    {
        return view('livewire.taxi.car_models');
    }

    public function autocompleteVendorSelected($carMake)
    {

        try {
            $this->car_make_id = $carMake['id'];
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }

    public function save()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $carModel = new CarModel();
            $carModel->name = $this->name;
            $carModel->car_make_id = $this->car_make_id;
            $carModel->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Car Model") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Car Model") . " " . __('creation failed!'));
        }
    }


    public function initiateEdit($id)
    {
        $this->reset();
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->car_make_id = $this->selectedModel->car_make_id;
        $this->emit('preselectedVendorEmit', $this->selectedModel->car_make->name ?? "");
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $this->selectedModel->name = $this->name;
            $this->selectedModel->car_make_id = $this->car_make_id;
            $this->selectedModel->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Car Model") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Car Model") . " " . __('update failed!'));
        }
    }

}
