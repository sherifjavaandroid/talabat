<?php

namespace App\Http\Livewire\Tables;


use App\Models\PackageTypePricing;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PackageTypePriceTable extends BaseDataTableComponent
{

    public $model = PackageTypePricing::class;

    public function query()
    {
        return PackageTypePricing::with('package_type')->where('vendor_id', Auth::user()->vendor_id);
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->sortable(),
            Column::make(__('Package Type'), 'package_type.name')->searchable(),
            Column::make(__('Max Booking Days'), 'max_booking_days')->sortable(),
            Column::make(__('Base Price'), 'base_price')->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "model" => $row,
                    "value" => $value
                ]);
            })->sortable(),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.crud_actions', $data = [
                    "model" => $row
                ]);
            }),
        ];
    }
}
