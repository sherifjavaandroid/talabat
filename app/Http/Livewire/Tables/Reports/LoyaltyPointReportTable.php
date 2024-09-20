<?php

namespace App\Http\Livewire\Tables\Reports;

use App\Exports\CouponsExport;
use App\Models\LoyaltyPointReport;
use Maatwebsite\Excel\Facades\Excel;

use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class LoyaltyPointReportTable extends BaseReportTable
{

    public $model = LoyaltyPointReport::class;

    public function query()
    {
        return LoyaltyPointReport::with('order', 'loyalty_point')
            ->when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('created_at', ">=", $sDate))
            ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('created_at', "<=", $eDate));
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id'),
            Column::make(__('Points'), 'points')->searchable()->sortable(),
            Column::make(__('Amount'), 'amount')
                ->format(function ($value, $column, $row) {
                    return view('components.table.price', $data = [
                        "value" => $value,
                        "model" => $row,
                    ]);
                })->searchable()->sortable(),
            Column::make(__('Order #'), 'order.code')
                ->format(function ($value, $column, $row) {
                    return view('components.table.order', $data = [
                        "value" => $value,
                        "model" => $row->order,
                    ]);
                }),
            Column::make(__('User'), 'order.user.name')
                ->format(function ($value, $column, $row) {
                    return view('components.table.user', $data = [
                        "value" => $value,
                        "model" => $row->order != null ? $row->order->user : $row->loyalty_point->user ?? null,
                    ]);
                }),
            Column::make(__('Type'), 'is_credit')->format(function ($value, $column, $row) {
                return view('components.table.chip', $data = [
                    "text" => $value ? __("Credit") : __("Debit"),
                    "textColor" => "font-bold text-lg text-white",
                    "bgColor" => $value ? "py-2 bg-green-500" : "py-2 bg-red-500",
                ]);
            }),
            Column::make(__('Date'), 'formatted_date_time'),
        ];
    }


    public function exportSelected()
    {

        $this->isDemo(true);
        if ($this->selectedRowsQuery->count() > 0) {
            return Excel::download(new CouponsExport($this->selectedKeys), 'coupons.xlsx');
        } else {
            //
        }
    }
}
