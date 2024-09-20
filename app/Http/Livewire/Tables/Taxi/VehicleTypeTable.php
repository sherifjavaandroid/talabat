<?php

namespace App\Http\Livewire\Tables\Taxi;

use App\Http\Livewire\Tables\BaseDataTableComponent;
use App\Models\VehicleType;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VehicleTypeTable extends BaseDataTableComponent
{

    public $model = VehicleType::class;

    public function query()
    {
        return VehicleType::query();
    }

    public function columns():array
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            $this->smImageColumn(),
            Column::make(__('Name'),'name')->searchable(),
            Column::make(__('Base Fare'),'base_fare')->searchable(),
            Column::make(__('Distance Fare')."/km",'distance_fare')->searchable(),
            Column::make(__('Fare Per Minutes'),'time_fare')->searchable(),
            Column::make(__('Minimum Fare'),'min_fare')->searchable(),
            $this->activeColumn(),
            $this->actionsColumn('components.buttons.no_delete_actions'),
        ];
    }
}
