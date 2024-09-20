<?php

namespace App\Http\Livewire\Tables\Reports;

use App\Http\Livewire\Tables\BaseDataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class BaseReportTable extends BaseDataTableComponent
{

    public array $bulkActions = [];
    public function filters(): array
    {
        return [
            'start_date' => Filter::make(__('Start Date'))
                ->date([
                    'min' => now()->subYear()->format('Y-m-d'), // Optional
                    'max' => now()->format('Y-m-d') // Optional
                ]),
            'end_date' => Filter::make(__('End Date'))
                ->date([
                    'min' => now()->subYear()->format('Y-m-d'), // Optional
                    'max' => now()->format('Y-m-d') // Optional
                ])
        ];
    }


    public function mount()
    {

        $this->bulkActions = [
            'exportSelected' => __('Export'),
        ];
    }
}
