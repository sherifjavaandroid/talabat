<?php

namespace App\Http\Livewire\Tables;


use App\Models\SubscriptionVendor;
use Kdion4891\LaravelLivewireTables\Column;

class VendorSubscriptionTable extends BaseTableComponent
{

    public $model = SubscriptionVendor::class;

    public function query()
    {
        return SubscriptionVendor::with('subscription','vendor')->whereHas('vendor',function($query){
            return $query->mine();
        });
    }

    public function columns()
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            Column::make(__('Vendor'),'vendor.name')->searchable()->sortable(),
            Column::make(__('Name'),'subscription.name')->searchable()->sortable(),
            Column::make(__('Status'),'status')->searchable()->sortable(),
            Column::make(__('Expires At'),'expires_at'),
            Column::make(__('Expired'),'expired')->view('components.table.bool'),
            Column::make(__('Actions'))->view('components.buttons.subscription_review'),
        ];
    }


}
