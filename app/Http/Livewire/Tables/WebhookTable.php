<?php

namespace App\Http\Livewire\Tables;

use App\Models\PaymentMethod;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WebhookTable extends BaseDataTableComponent
{

    public $model = PaymentMethod::class;
    public $header_view = 'components.buttons.new';


    public function query()
    {
        return PaymentMethod::where('is_cash', 0)->where('slug','!=','offline');
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Payment Method'), 'name')->searchable()->sortable(),
            Column::make(__('Link'), 'wehbook_link'),
            Column::make(__('Active'))->format(function ($value, $column, $row) {
                return view('components.table.active', $data = [
                    "model" => $row
                ]);
            }),
            Column::make(__('Created At'), 'formatted_date'),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.webhook_actions', $data = [
                    "model" => $row
                ]);
            }),
        ];
    }
}
