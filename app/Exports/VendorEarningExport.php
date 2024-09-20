<?php

namespace App\Exports;

use App\Models\EarningReport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class VendorEarningExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $selectedIds;
    public function __construct($selectedIds)
    {
        $this->selectedIds = $selectedIds;
    }


    public function query()
    {
        return EarningReport::query()->whereIn("id", $this->selectedIds);
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
            __("Vendor"),
            __("Earning"),
            __("Commission"),
            __("Balance"),
            __("Rate")."(%)",
            __("Order"),
            __("Created at"),
        ];
    }


    public function map($model): array
    {
        return [
            $model->id,
            $model->earnings->vendor->name,
            $model->earning ?? 0.00,
            $model->commission ?? 0.00,
            $model->balance ?? 0.00,
            $model->rate ?? '',
            $model->order->code ?? "",
            $model->created_at,
        ];
    }
}
