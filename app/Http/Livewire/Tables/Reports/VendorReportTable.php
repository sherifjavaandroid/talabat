<?php

namespace App\Http\Livewire\Tables\Reports;

use App\Exports\VendorReportExport;
use App\Models\Vendor;
use Maatwebsite\Excel\Facades\Excel;

use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class VendorReportTable extends BaseReportTable
{

    public $model = Vendor::class;

    public function query()
    {
        //
        return $this->model::mine()->withCount(['sales' => function ($query) {
            $query->when($this->getFilter('start_date'), function ($query, $sDate) {
                return $query->whereDate('created_at', ">=", $sDate);
            })->when($this->getFilter('end_date'), function ($query, $eDate) {
                return $query->whereDate('created_at', "<=", $eDate);
            });
        }])
            ->withCount(['successful_sales' => function ($query) {
                $query->when($this->getFilter('start_date'), function ($query, $sDate) {
                    return $query->whereDate('created_at', ">=", $sDate);
                })->when($this->getFilter('end_date'), function ($query, $eDate) {
                    return $query->whereDate('created_at', "<=", $eDate);
                });
            }])
            ->withCount(['cancelled_sales' => function ($query) {
                $query->when($this->getFilter('start_date'), function ($query, $sDate) {
                    return $query->whereDate('created_at', ">=", $sDate);
                })->when($this->getFilter('end_date'), function ($query, $eDate) {
                    return $query->whereDate('created_at', "<=", $eDate);
                });
            }])
            ->withCount(['pending_sales' => function ($query) {
                $query->when($this->getFilter('start_date'), function ($query, $sDate) {
                    return $query->whereDate('created_at', ">=", $sDate);
                })->when($this->getFilter('end_date'), function ($query, $eDate) {
                    return $query->whereDate('created_at', "<=", $eDate);
                });
            }]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id'),
            Column::make(__('Logo'))->format(function ($value, $column, $row) {
                return view('components.table.logo', $data = [
                    "model" => $row
                ]);
            }),
            Column::make(__('Vendor'), "name")->searchable()->sortable(),
            Column::make(__('Order Count'), 'sales_count')->format(function ($value, $column, $row) {
                return view('components.table.count', $data = [
                    "model" => $row,
                    "value" => $value
                ]);
            })->sortable(),
            Column::make(__('Successful Orders'), 'successful_sales_count')->format(function ($value, $column, $row) {
                return view('components.table.count', $data = [
                    "model" => $row,
                    "value" => $value
                ]);
            })->sortable(),
            Column::make(__('Cancelled Orders'), 'cancelled_sales_count')->format(function ($value, $column, $row) {
                return view('components.table.count', $data = [
                    "model" => $row,
                    "value" => $value
                ]);
            })->sortable(),
            Column::make(__('Pending Orders'), 'pending_sales_count')->format(function ($value, $column, $row) {
                return view('components.table.count', $data = [
                    "model" => $row,
                    "value" => $value
                ]);
            })->sortable(),
        ];
    }


    public function exportSelected()
    {
        $this->isDemo(true);
        if ($this->selectedRowsQuery->count() > 0) {
            $fileName = "vendor_report(";
            //
            if ($this->getFilter('start_date')) {
                $fileName .= $this->getFilter('start_date') . " - ";
            }
            //
            else if ($this->getFilter('end_date')) {
                $fileName .= $this->getFilter('end_date');
            }

            $fileName .= ").xlsx";
            return Excel::download(new VendorReportExport($this->selectedRowsQuery), $fileName);
        } else {
            //
        }
    }
}
