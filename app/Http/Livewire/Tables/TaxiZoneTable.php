<?php

namespace App\Http\Livewire\Tables;

use App\Models\TaxiZone;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class TaxiZoneTable extends BaseDataTableComponent
{

    public $model = TaxiZone::class;
    public string $defaultSortColumn = 'is_active';
    public string $defaultSortDirection = 'desc';

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

    public function query()
    {
        return $this->model::when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('created_at', ">=", $sDate))
            ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('created_at', "<=", $eDate));
    }

    public function columns(): array
    {
        $columns = [
            $this->indexColumn(),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            $this->activeColumn()->sortable(),
            Column::make(__('Created At'), 'formatted_date'),

        ];


        if (app()->environment('production')) {
            $columns[] = Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.simple_actions', $data = [
                    "model" => $row
                ]);
            });
        }

        return $columns;
    }
}