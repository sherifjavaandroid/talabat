<?php

namespace App\Http\Livewire\Tables;

use App\Exports\OrdersExport;
use App\Models\Order;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

use Illuminate\Support\Facades\Auth;

class FleetOrderTable extends BaseDataTableComponent
{


    public $per_page = 10;

    public $dataListQuery;
    public array $bulkActions = [];

    public function mount()
    {
        $this->bulkActions = [
            'exportSelected' => __('Export'),
        ];
    }


    public function filters(): array
    {
        return [
            'status' => Filter::make(__("Status"))
                ->select([
                    '' => __('Any'),
                    'scheduled' => __('Scheduled'),
                    'pending' => __('Pending'),
                    'preparing' => __('Preparing'),
                    'ready' => __('Ready'),
                    'enroute' => __('Enroute'),
                    'delivered' => __('Delivered'),
                    'cancelled' => __('Cancelled'),
                    'failed' => __('Failed'),
                ]),
            'payment_status' => Filter::make(__("Payment Status"))
                ->select([
                    '' => __('Any'),
                    'pending' => __('Pending'),
                    'review' => __('Review'),
                    'successful' => __('Successful'),
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

        $query = Order::with('vendor')->whereHas("driver", function ($query) {
            return $query->whereHas("fleets", function ($query) {
                return $query->where('id', \Auth::user()->fleet()->id ?? null);
            });
        });


        return $query->when($this->getFilter('status'), fn ($query, $status) => $query->currentStatus($status))
            ->when($this->getFilter('payment_status'), fn ($query, $pStatus) => $query->where('payment_status', $pStatus))
            ->when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('created_at', ">=", $sDate))
            ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('created_at', "<=", $eDate));
    }

    public function columns(): array
    {

        $columns = [
            Column::make(__('ID'), 'id')->searchable()->sortable(),
            Column::make(__('Code'), 'code')->searchable()->sortable(),
            Column::make(__('User'), 'user.name')->searchable(),
            Column::make(__('Status'), 'status')
                ->format(function ($value, $column, $row) {
                    return view('components.table.custom', $data = [
                        "value" => \Str::ucfirst($row->status)
                    ]);
                }),
            Column::make(__('Payment'), 'payment_status')
                ->format(function ($value, $column, $row) {
                    return view('components.table.custom', $data = [
                        "value" => \Str::ucfirst($row->payment_status)
                    ]);
                })->searchable(),
            Column::make(__('Total'), 'total')->format(function ($value, $column, $row) {
                return view('components.table.order-total', $data = [
                    "model" => $row
                ]);
            })->searchable()
                ->sortable(),
            Column::make(__('Method'), 'payment_method.name')->searchable(),
        ];

        //
        // if (Auth::user()->hasAnyRole('admin', 'city-admin')) {
        //     // array_push($columns, Column::make(__('Vendor'), 'vendor.name'));
        // }

        array_push($columns, Column::make(__('Created At'), 'formatted_date'));
        array_push($columns, Column::make(__('Actions'))->format(function ($value, $column, $row) {
            return view('components.buttons.show', $data = [
                "model" => $row
            ]);
        }));
        return $columns;
    }


    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            return Excel::download(new OrdersExport($this->selectedKeys), 'orders.xlsx');
        } else {
            //
        }
    }
}
