<?php

namespace App\Http\Livewire\Tables;

use App\Models\Fleet;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;


class FleetTable extends BaseDataTableComponent
{

    public $model = Fleet::class;




    public function query()
    {

        return $this->model::query();
    }


    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            Column::make(__('Email'), 'email')->searchable()->sortable(),
            Column::make(__('Phone'), 'phone')->searchable()->sortable(),
            Column::make(__('Address'), 'address')->searchable()->sortable(),
            $this->actionsColumn('components.buttons.fleet_actions'),
        ];

        

       
    }

}
