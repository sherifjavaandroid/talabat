<?php

namespace App\Http\Livewire\Tables;

use App\Models\Brand;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Spatie\Permission\Models\Role;

class RoleTable extends BaseDataTableComponent
{

    public $model = Role::class;
    

    public function query()
    {
        return $this->model::withCount('permissions');
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            Column::make(__('Name'),'name')->searchable()->sortable(),
            Column::make(__('Permissions'),'permissions_count'),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.edit', $data = [
                    "model" => $row
                ]);
            }),
        ];
    }
}
