<?php

namespace App\Http\Livewire\Tables;


use App\Models\Remittance;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
//import Auth facade
use Illuminate\Support\Facades\Auth;

class RemittanceTable extends BaseDataTableComponent
{

    public $model = Remittance::class;
    public $type;


    public function filters(): array
    {
        return [
            'start_date' => Filter::make(__('Start Date'))
                ->date([
                    'min' => now()->subYear()->format('Y-m-d'), // Optional
                    'max' => now()->format('Y-m-d') // Optional
                ]),
            'end_date' => Filter::make(__('End Date'))
                ->date([
                    'min' => now()->subYear()->format('Y-m-d'), // Optional
                    'max' => now()->format('Y-m-d') // Optional
                ])
        ];
    }



    public function query()
    {
        $vendorId = \Auth::user()->vendor_id;
        return Remittance::with('user', 'order')
            ->whereNotNull('user_id')
            ->when($vendorId, function ($query) use ($vendorId) {
                return $query->whereHas("user", function ($q) use ($vendorId) {
                    return $q->where('vendor_id', $vendorId);
                });
            })->when(\Auth::user()->hasAnyRole('city-admin'), function ($query) {
                return $query->whereHas("user", function ($query) {
                    return $query->where('creator_id', Auth::id());
                });
            })->where('status', "pending")
            ->when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('created_at', ">=", $sDate))
            ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('created_at', "<=", $eDate));
    }

    public function columns(): array
    {

        $columns = [
            Column::make(__('ID'), 'id')->searchable()->sortable(),
            Column::make(__('Amount'), 'order_total')
                ->format(function ($value, $column, $row) {
                    return view('components.table.price', $data = [
                        "value" => $value
                    ]);
                }),
            Column::make(__('Earned'), 'earned')
                ->format(function ($value, $column, $row) {
                    return view('components.table.price', $data = [
                        "value" => $value
                    ]);
                }),

            Column::make(__('Driver'), 'user.name')->searchable(),
            Column::make(__('Updated At'), 'formatted_updated_date'),
            Column::make(__('Actions'))
                ->format(function ($value, $column, $row) {
                    return view('components.buttons.remittance_actions', $data = [
                        "model" => $row,
                        "value" => $value,
                    ]);
                }),
        ];



        return $columns;
    }
}
