<?php

namespace App\Http\Livewire\Tables;


use App\Models\Order;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UserOrderTable extends BaseDataTableComponent
{


    public $dataListQuery;
    public $userId;

    public function mount($userId)
    {
        $this->userId = $userId;
    }



    public function query()
    {
        return Order::whereUserId($this->userId);
    }

    public function columns(): array
    {

        return [
            Column::make(__('ID'), 'id')->searchable()->sortable(),
            Column::make(__('Code'), 'code')
                ->format(function ($value, $column, $row) {
                    return view('components.table.order', $data = [
                        "value" => $value,
                        "model" => $row,
                    ]);
                })->searchable()->sortable(),
            Column::make(__('Status'), 'status')
                ->format(function ($value, $column, $row) {
                    return view('components.table.custom', $data = [
                        "value" => __(\Str::ucfirst($row->status))
                    ]);
                }),
            Column::make(__('Total'), 'total')->format(function ($value, $column, $row) {
                return view('components.table.order-total', $data = [
                    "model" => $row
                ]);
            })->searchable()
                ->sortable(),
            Column::make(__('Created At'), 'created_at'),
        ];
    }
}
