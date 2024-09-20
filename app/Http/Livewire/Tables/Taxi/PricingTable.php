<?php

namespace App\Http\Livewire\Tables\Taxi;

use App\Http\Livewire\Tables\BaseDataTableComponent;
use App\Models\TaxiCurrencyPricing;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PricingTable extends BaseDataTableComponent
{

    public $model = TaxiCurrencyPricing::class;
    public $header_view = 'components.buttons.new';

    public function query()
    {
        return TaxiCurrencyPricing::with('currency','vehicle_type');
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Vehicle Type'), 'vehicle_type.name'),
            Column::make(__('Currency'), 'currency.code'),
            Column::make(__('Base Fare'), 'base_fare')->searchable(),
            Column::make(__('Distance Fare') . "/km", 'distance_fare')->searchable(),
            Column::make(__('Fare Per Minutes'), 'time_fare')->searchable(),
            Column::make(__('Minimum Fare'), 'min_fare')->searchable(),
            Column::make(__('Active'))->format(function ($value, $column, $row) {
                return view('components.table.active', $data = [
                    "model" => $row
                ]);
            }),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.no_delete_actions', $data = [
                    "model" => $row
                ]);
            }),
        ];
    }
}
