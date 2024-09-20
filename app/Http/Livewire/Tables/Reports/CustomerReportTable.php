<?php

namespace App\Http\Livewire\Tables\Reports;

use App\Exports\CustomerReportExport;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;

use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class CustomerReportTable extends BaseReportTable
{

    public $model = Order::class;

    public function filters(): array
    {
        return [
            'status' => Filter::make(__("Status"))
                ->select([
                    '' => __('Any'),
                    'pending' => __('Pending'),
                    'preparing' => __('Preparing'),
                    'ready' => __('Ready'),
                    'enroute' => __('Enroute'),
                    'delivered' => __('Delivered'),
                    'cancelled' => __('Cancelled'),
                    'failed' => __('Failed'),
                ]),
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
        //
        return $this->model::with('user')->mine()->selectRaw('sum(total) as total_amount,count(*) as purchases, user_id,id')
            ->when($this->getFilter('status'), function ($query, $status) {
                return $query->currentStatus($status);
            })->when($this->getFilter('start_date'), function ($query, $sDate) {
                return $query->whereDate('created_at', ">=", $sDate);
            })->when($this->getFilter('end_date'), function ($query, $eDate) {
                return $query->whereDate('created_at', "<=", $eDate);
            })->groupBy('user_id');
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id'),
            Column::make(__('Name'), 'user.name')->searchable()->sortable(),
            Column::make(__('Order Count'), 'purchases')->format(function ($value, $column, $row) {
                return view('components.table.count', $data = [
                    "model" => $row,
                    "value" => $value
                ]);
            })->sortable(),
            Column::make(__('Total Amount'), 'total_amount')->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "model" => $row,
                    "column" => $column,
                    "value" => number_format($value, 2),
                ]);
            })->sortable(),
        ];
    }



    public function exportSelected()
    {
        $this->isDemo(true);
        //
        if ($this->selectedRowsQuery->count() > 0) {
            $fileName = "customer_report(";
            //
            if ($this->getFilter('start_date')) {
                $fileName .= $this->getFilter('start_date') . " - ";
            }
            //
            else if ($this->getFilter('end_date')) {
                $fileName .= $this->getFilter('end_date');
            }

            $fileName .= ").xlsx";

            //
            $dataSet = $this->model::with('user')->mine()->selectRaw('sum(total) as total_amount,count(*) as purchases, user_id,id')
                ->when($this->getFilter('status'), function ($query, $status) {
                    return $query->currentStatus($status);
                })->when($this->getFilter('start_date'), function ($query, $sDate) {
                    return $query->whereDate('created_at', ">=", $sDate);
                })->when($this->getFilter('end_date'), function ($query, $eDate) {
                    return $query->whereDate('created_at', "<=", $eDate);
                })->groupBy('user_id')->get()->toArray();
            //
            return Excel::download(new CustomerReportExport($dataSet), $fileName);
        } else {
            //
        }
    }
}
