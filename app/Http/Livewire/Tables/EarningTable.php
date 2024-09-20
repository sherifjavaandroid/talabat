<?php

namespace App\Http\Livewire\Tables;

use App\Models\Earning;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Illuminate\Support\Facades\Auth;

class EarningTable extends BaseDataTableComponent
{

    public $model = Earning::class;
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
        return Earning::with('user', 'vendor')->when($this->type == "vendors", function ($query) {
            return $query->whereNotNull('vendor_id')->when(\Auth::user()->hasAnyRole('city-admin'), function ($query) {
                return $query->whereHas("vendor", function ($query) {
                    return $query->where('creator_id', Auth::id());
                });
            });
        }, function ($query) use ($vendorId) {
            return $query->whereNotNull('user_id')->when($vendorId, function ($query) use ($vendorId) {
                return $query->whereHas("user", function ($q) use ($vendorId) {
                    return $q->where('vendor_id', $vendorId);
                });
            })->when(\Auth::user()->hasAnyRole('city-admin'), function ($query) {
                return $query->whereHas("user", function ($query) {
                    return $query->where('creator_id', Auth::id());
                });
            });
        })
        ->when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('updated_at', ">=", $sDate))
        ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('updated_at', "<=", $eDate));
    }

    public function columns(): array
    {

        $columns = [
            Column::make(__('ID'),"id"),
            Column::make(__('Amount'),"amount")->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "model" => $row,
                    "value" => $value,
                ]);
            })->searchable()->sortable(),
        ];


        if ($this->type == "vendors") {
            array_push($columns, Column::make(__('Vendor'), 'vendor.name')->searchable());
        } else {
            array_push($columns, Column::make(__('Driver'), 'user.name')->searchable());
        }

        $columns[] =  Column::make(__('Updated At'), 'formatted_updated_date');
        $columns[] = Column::make(__('Actions'))->format(function ($value, $column, $row) {
            return view('components.buttons.earning_actions', $data = [
                "model" => $row
            ]);
        });
        return $columns;
    }
}
