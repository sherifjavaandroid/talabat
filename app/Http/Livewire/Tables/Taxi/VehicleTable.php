<?php

namespace App\Http\Livewire\Tables\Taxi;

use App\Models\Vehicle;
use App\Http\Livewire\Tables\BaseDataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VehicleTable extends BaseDataTableComponent
{

    public $model = Vehicle::class;

    public function query()
    {
        return Vehicle::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Driver'), 'driver.name')->searchable(),
            Column::make(__('Registration Number'), 'reg_no')->searchable(),
            Column::make(__('Color'), 'color'),
            Column::make(__('Car Make'), 'car_model.car_make.name')->searchable(),
            Column::make(__('Car Model'), 'car_model.name')->searchable(),
            Column::make(__('Verified'), 'verified')->format(function ($value, $column, $row) {
                return view('components.table.chip', $data = [
                    "text" => __($value ? "Verified" : "UnVerified"),
                    "bgColor" => $value ? "bg-green-500" : "bg-red-500",
                    "textColor" => "text-white",
                ]);
            })->sortable(),
            $this->activeColumn(),
            $this->actionsColumn('components.buttons.crud_actions'),
        ];
    }

    public function activateModel()
    {

        try {
            if ($this->checkDemo) {
                $this->isDemo();
            }
            if(!$this->selectedModel->verified){
                throw new \Exception(__("You can't activate unverified vehicle. Please verify first before activating vehicle."));
            }
            $this->selectedModel->is_active = true;
            $this->selectedModel->save();
            $this->showSuccessAlert(__("Activated"));
        } catch (\Exception $error) {
            $this->showErrorAlert( $error->getMessage() ?? "Failed");
        }
    }
}
