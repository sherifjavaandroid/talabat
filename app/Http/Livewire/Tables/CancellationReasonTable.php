<?php

namespace App\Http\Livewire\Tables;

use App\Models\CancellationReason;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CancellationReasonTable extends BaseDataTableComponent
{

    public $model = CancellationReason::class;

    public function query()
    {
        return CancellationReason::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('Type'), 'type')->addClass('break-all w-64')->searchable(),
            Column::make(__('Reason'), 'reason')->searchable(),
            Column::make(__('Created At'), 'created_at')->addClass('break-all w-64'),
            Column::make(__('Action'))->addClass('w-20')->format(function ($value, $column, $row) {
                return view('components.buttons.edit', $data = [
                    "model" => $row
                ]);
            }),
            Column::make("")->addClass('w-20')->format(function ($value, $column, $row) {
                return view('components.buttons.delete', $data = [
                    "model" => $row
                ]);
            }),
        ];
    }
}
