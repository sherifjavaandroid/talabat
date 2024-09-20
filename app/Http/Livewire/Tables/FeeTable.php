<?php

namespace App\Http\Livewire\Tables;

use App\Models\Fee;
use Rappasoft\LaravelLivewireTables\Views\Column;

class FeeTable extends BaseDataTableComponent
{

    public $model = Fee::class;
    public $header_view = 'components.buttons.new';


    public function query()
    {
        return Fee::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Name'), "name")->searchable()->sortable(),
            Column::make(__('Value'), "value")->searchable()->sortable(),
            Column::make(__('For Admin'), 'for_admin')->format(function ($value, $column, $row) {
                return view('components.table.bool', [
                    "model" => $row,
                    "isTrue" => $value,
                ]);
            }),
            Column::make(__('Percentage'),'percentage')->format(function ($value, $column, $row) {
                return view('components.table.bool', [
                    "model" => $row,
                    "isTrue" => $value,
                ]);
            }),
            Column::make(__('Active'))->format(function ($value, $column, $row) {
                return view('components.table.active', $data = [
                    "model" => $row
                ]);
            }),

            Column::make(__('Created At'), 'formatted_date'),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.actions', $data = [
                    "model" => $row
                ]);
            }),
        ];
    }
}
