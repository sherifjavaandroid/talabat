<?php

namespace App\Http\Livewire\Tables;


use App\Models\Order;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VendorOrderTable extends BaseDataTableComponent
{


    public $dataListQuery;
    public $vendorId;

    public function mount($vendorId)
    {
        $this->vendorId = $vendorId;
    }



    public function query()
    {

        return Order::where('vendor_id', $this->vendorId);
    }

    public function columns(): array
    {

        return [
            $this->indexColumn(),
            Column::make(__('Code'), 'code')->searchable()->sortable(),
            Column::make(__('User'), 'user.name')->searchable(),

            Column::make(__('Status'), 'status')
                ->format(function ($value, $column, $row) {

                    $text = __(\Str::ucfirst($row->status));
                    return view('components.table.plain', $data = [
                        "text" => $text
                    ]);
                }),
            Column::make(__('Payment Status'), 'payment_status')
                ->format(function ($value, $column, $row) {
                    $text = "<span class='text-xs' style='color:$row->payment_status_color;'>" . __(\Str::ucfirst($row->payment_status)) . "</span>";
                    return view('components.table.plain', $data = [
                        "text" => $text
                    ]);
                }),
            Column::make(__('Total'), 'total')->format(function ($value, $column, $row) {
                $text = currencyFormat($row->total, $row->currency_symbol);
                return view('components.table.plain', $data = [
                    "text" => $text
                ]);
            })->searchable()
                ->sortable(),
            Column::make(__('Method'), 'payment_method.name')->searchable(),
        ];
    }
}
