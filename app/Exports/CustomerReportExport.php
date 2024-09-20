<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class CustomerReportExport implements FromArray, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $selectedRows;
    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }

    public function array():array
    {
        return $this->selectedRows;
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
            __("Name"),
            __("Order Count"),
            __("Total Amount"),
        ];
    }


    public function map($model): array
    {
        return [
            $model["id"],
            $model["user"]["name"],
            $model['purchases'] != null ? $model['purchases'] : "0",
            $model["total_amount"] != null ? $model["total_amount"] : "0",
        ];
    }
}
