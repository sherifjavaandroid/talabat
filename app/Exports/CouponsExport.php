<?php

namespace App\Exports;

use App\Models\CouponUser;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class CouponsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $ids;
    public function __construct($ids)
    {
        $this->ids = $ids;
    }


    public function query()
    {
        return CouponUser::query()->whereIn("id", $this->ids);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
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
            __("Discount"),
            __("User"),
            __("Order"),
            __("Created at"),
        ];
    }


    public function map($order): array
    {

        return [
            $order->id,
            $order->code,
            $order->discount != null ? $order->discount : 0.00,
            $order->user->name,
            $order->order->code ?? "",
            $order->created_at,
        ];
    }
}
