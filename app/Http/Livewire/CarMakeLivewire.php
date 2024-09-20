<?php

namespace App\Http\Livewire;

use App\Models\CarMake;
use Illuminate\Support\Facades\DB;
use Exception;

class CarMakeLivewire extends BaseLivewireComponent
{

    public $model = CarMake::class;

    public $name;

    protected $rules = [
        "name" => "required|string",
    ];

    public function render()
    {
        return view('livewire.taxi.car_makes');
    }

    public function save()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $carMake = new CarMake();
            $carMake->name = $this->name;
            $carMake->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Car Make") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Car Make") . " " . __('creation failed!'));
        }
    }


    public function initiateEdit($id)
    {
        $this->reset();
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $this->selectedModel->name = $this->name;
            $this->selectedModel->save();
            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Car Make") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Car Make") . " " . __('update failed!'));
        }
    }

}
