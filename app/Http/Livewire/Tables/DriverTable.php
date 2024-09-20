<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DriverTable extends BaseDataTableComponent
{

    public $model = User::class;

    public function query()
    {
        return User::role('driver')->where('vendor_id', \Auth::user()->vendor_id);
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            Column::make(__('Name'),'name')->searchable()->sortable(),
            Column::make(__('Email'),'email')->searchable()->sortable(),
            Column::make(__('Phone'),'phone')->searchable()->sortable(),
            Column::make(__('Wallet'),'wallet')->format(function ($value, $column, $row) {
                return view('components.table.wallet', $data = [
                    "model" => $row
                ]);
            }),
            Column::make(__('Commission')."(%)", 'commission'),
            Column::make(__('Role'), 'role_name'),
            Column::make(__('Created At'), 'formatted_date'),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.actions', $data = [
                    "model" => $row
                ]);
            }),
        ];
    }
}
