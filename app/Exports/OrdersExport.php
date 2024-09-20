<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class OrdersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $orderIds;
    public function __construct($orderIds)
    {
        $this->orderIds = $orderIds;
    }


    public function query()
    {
        return Order::query()->whereIn("id", $this->orderIds);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:O1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

    public function headings(): array
    {

        return [
            __("ID"),
            __("Code"),
            __("Vendor"),
            __("User"),
            __("Driver"),
            __("Status"),
            __("Payment Status"),
            __("SubTotal"),
            __("Discount"),
            __("Tip"),
            __("Delivery Fee"),
            __("Tax"),
            __("Total"),
            __("Payment Method"),
            __("Created at"),
        ];
    }


    public function map($order): array
    {
        if (!empty($order->taxi_order)) {

            return [
                $order->id,
                $order->code,
                __("Taxi Order"),
                $order->user->name ?? "##Undefined##",
                $order->driver->name ?? "##Undefined##",
                $order->status,
                $order->payment_status,
                $order->sub_total != null ? $order->sub_total : 0.00,
                $order->discount != null ? $order->discount : 0.00,
                $order->tip != null ? $order->tip : 0.00,
                $order->delivery_fee != null ? $order->delivery_fee : 0.00,
                $order->tax != null ? $order->tax : 0.00,
                $order->total != null ? $order->total : 0.00,
                $order->payment_method->name ?? "##Undefined##",
                $order->created_at,
            ];
        }

        //regular orders
        return [
            $order->id,
            $order->code,
            $order->vendor->name ?? "##Undefined##",
            $order->user->name ?? "##Undefined##",
            $order->driver->name ?? "##Undefined##",
            $order->status,
            $order->payment_status,
            $order->sub_total != null ? $order->sub_total : 0.00,
            $order->discount != null ? $order->discount : 0.00,
            $order->tip != null ? $order->tip : 0.00,
            $order->delivery_fee != null ? $order->delivery_fee : 0.00,
            $order->tax != null ? $order->tax : 0.00,
            $order->total != null ? $order->total : 0.00,
            $order->payment_method->name ?? "##Undefined##",
            $order->created_at,
        ];
    }
}
