<?php

namespace App\Http\Livewire\Tables\Taxi;

use App\Models\Vehicle;
use App\Http\Livewire\Tables\BaseDataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class FleetVehicleTable extends BaseDataTableComponent
{

    public $model = Vehicle::class;

    public function query()
    {
        return Vehicle::whereHas('fleets', function($query){
            return $query->where('id',\Auth::user()->fleet()->id ?? null);
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            Column::make(__('Driver'),'driver.name')->searchable(),
            Column::make(__('Registration Number'),'reg_no')->searchable(),
            Column::make(__('Color'),'color'),
            Column::make(__('Car Make'),'car_model.car_make.name')->searchable(),
            Column::make(__('Car Model'),'car_model.name')->searchable(),
            $this->activeColumn(),
            $this->actionsColumn('components.buttons.crud_actions'),
        ];
    }
}
