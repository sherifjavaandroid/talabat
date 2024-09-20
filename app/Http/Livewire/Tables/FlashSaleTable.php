<?php

namespace App\Http\Livewire\Tables;

use App\Models\FlashSale;
use Rappasoft\LaravelLivewireTables\Views\Column;

class FlashSaleTable extends BaseDataTableComponent
{

    public $model = FlashSale::class;
    public $header_view = 'components.buttons.new';


    public function query()
    {
        return FlashSale::withCount('items','vendor_type');
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'),"id")->sortable(),
            Column::make(__('Vendor Type'),'vendor_type.name'),
            Column::make(__('Name'),'name')->searchable(),
            Column::make(__('Items'),'items_count')->sortable(),
            Column::make(__('Created At'), 'formatted_date'),
            Column::make(__('Expires On'), 'expires_at'),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.actions', $data = [
                    "model" => $row
                ]);
            }),
        ];
    }
}
