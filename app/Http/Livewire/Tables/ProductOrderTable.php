<?php

namespace App\Http\Livewire\Tables;


use App\Models\OrderProduct;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ProductOrderTable extends BaseDataTableComponent
{


    public $dataListQuery;
    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
    }



    public function query()
    {

        return OrderProduct::where('product_id', $this->productId)->with('order');
        // return Order::whereHas('products', function ($query) {
        //     $query->where('product_id', $this->productId);
        // })->with('products');
    }

    public function columns(): array
    {

        return [
            $this->indexColumn(),
            Column::make(__('Code'), 'code')
                ->format(function ($value, $column, $row) {
                    $value = $row->order->code;
                    $order = $row->order;
                    return view('components.table.order', $data = [
                        "value" => $value,
                        "model" => $order,
                    ]);
                })->searchable()->sortable(),

            Column::make(__('Qty'), 'quantity')->searchable()->sortable(),
            Column::make(__('Price'), 'price')->format(function ($value, $column, $row) {
                return view('components.table.price', $data = [
                    "value" => $value
                ]);
            })->searchable()
                ->sortable(),
            Column::make(__('Total'), 'price')->format(function ($value, $column, $row) {
                $value = $row->price * $row->quantity;
                return view('components.table.price', $data = [
                    "value" => $value
                ]);
            })->searchable()
                ->sortable(),
            Column::make(__('Status'), 'status')
                ->format(function ($value, $column, $row) {
                    $value = $row->order->status;
                    return view('components.table.custom', $data = [
                        "value" => __(\Str::ucfirst($value))
                    ]);
                }),
            Column::make(__('Created At'), 'created_at'),
        ];
    }
}
