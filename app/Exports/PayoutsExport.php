<?php

namespace App\Exports;

use App\Models\Payout;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;


class PayoutsExport extends BaseExport implements WithMapping
{

    public function collection()
    {
        return Payout::with('user', 'earning.vendor', 'earning.user')->get();
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
            __('Payment Account'),
            __('Payment Method'),
            "",
            __('Paid By'),
            __('Status'),
            __("Created At"),
            __("Updated At"),
        ];
    }


    public function map($model): array
    {
        if ($model->earning) {
            $isDriver =  $model->earning->user_id != null;
            if ($model->earning->vendor == null && $model->earning->user == null) {
                $name = "";
            } else {
                $name = (!$isDriver ? $model->earning->vendor->name : $model->earning->user->name) ?? "";
            }
        } else {
            $isDriver = false;
            $name = "";
        }

        //
        return [
            $model["id"],
            !$isDriver ? __("Vendor") : __("Driver"),
            $name,
            $model["amount"] ?? 0.00,
            $model->payment_account != null ? "{$model->payment_account->name}({$model->payment_account->number})" : "",
            $model->payment_method->name ?? "",
            "",
            $model->user->name ?? "",
            $model->status,
            $model["created_at"],
            $model["updated_at"],
        ];
    }
}
