<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class CommissionReportExport implements FromArray, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $selectedRows;
    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }

    public function array(): array
    {
        return $this->selectedRows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:O1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
            },
        ];
    }

    public function headings(): array
    {

        return [
            __("ID"),
            __('Order No'),
            __("Vendor"),
            __("Vendor Earned"),
            __("Driver"),
            __("Driver Earned"),
            __("System Earned"),
            __("Created At"),
            "",
            __("Total Order Amount"),
            __("Delivery Fee"),
        ];
    }


    public function map($model): array
    {
        return [
            $model["id"],
            $model["order"]["code"] ?? "",
            $model["order"]["vendor"]["name"] ?? "",
            $model["vendor_commission"],
            $model["order"]["driver"]["name"] ?? "",
            $model["driver_commission"],
            $model["admin_commission"],
            $model["created_at"],
            "",
            $model["order"]["total"],
            $model["order"]["delivery_fee"],
        ];
    }
}
