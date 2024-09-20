<?php

namespace App\Http\Livewire\Tables;



use App\Models\Subscription;
use Kdion4891\LaravelLivewireTables\Column;

class SubscriptionTable extends BaseTableComponent
{

    public $model = Subscription::class;
    public $header_view = 'components.buttons.new';

    public function query()
    {
        return Subscription::query();
    }

    public function columns()
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            Column::make(__('Name'),'name')->searchable()->sortable(),
            Column::make(__('Days'),'days')->searchable()->sortable(),
            Column::make(__('Qty'),'qty')->searchable()->sortable(),
            Column::make(__('Amount'),'amount')->searchable()->sortable(),
            Column::make(__('Actions'))->view('components.buttons.actions'),
        ];
    }


}
