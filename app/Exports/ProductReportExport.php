<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class ProductReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $selectedRowsQuery;
    public function __construct($selectedRowsQuery)
    {
        $this->selectedRowsQuery = $selectedRowsQuery;
    }



    public function collection()
    {
        return $this->selectedRowsQuery->get();
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
            __("Name"),
            __("Vendor"),
            __("Order Count"),
        ];
    }


    public function map($model): array
    {
        return [
            $model->id,
            $model->name,
            $model->vendor->name ?? "",
            $model->sales_count ?? "0",
        ];
    }
}
