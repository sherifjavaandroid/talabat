<?php

namespace App\Exports;

use App\Models\Subscription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class SubscriptionReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
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
            __("Vendor Count"),
            __("Successful"),
            __("Pending"),
            __("Failed"),
        ];
    }


    public function map($model): array
    {
        return [
            $model->id,
            $model->name,
            $model->vendors_count != null ? $model->vendors_count : "0",
            $model->successful_sub_count != null ? $model->successful_sub_count : "0",
            $model->pending_sub_count != null ? $model->pending_sub_count : "0",
            $model->failed_sub_count != null ? $model->failed_sub_count : "0",
        ];
    }
}
