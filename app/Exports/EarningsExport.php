<?php

namespace App\Exports;

use App\Models\Earning;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;


class EarningsExport extends BaseExport implements WithMapping
{
    public function collection()
    {
        return Earning::with('user', 'vendor')->get();
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
            __("Type"),
            __("Name"),
            __('Amount'),
            __("Created At"),
            __("Updated At"),
        ];
    }


    public function map($model): array
    {

        $isDriver =  $model["user_id"] != null;
        if ($model->vendor == null && $model->user == null) {
            $name = "";
        } else {
            $name = (!$isDriver ? $model["vendor"]["name"] : $model["user"]["name"]) ?? "";
        }
        //
        return [
            $model["id"],
            !$isDriver ? __("Vendor") : __("Driver"),
            $name,
            currencyValueFormat($model["amount"] ?? 0.00),
            $model["created_at"],
            $model["updated_at"],
        ];
    }
}
