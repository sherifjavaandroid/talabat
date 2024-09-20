<?php

namespace App\Http\Livewire\Tables;

use App\Models\ProductReview;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class ProductReviewTable extends BaseDataTableComponent
{

    public $model = ProductReview::class;

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
        return $this->model::with('user', 'product')
            ->when($this->getFilter('start_date'), fn ($query, $sDate) => $query->whereDate('created_at', ">=", $sDate))
            ->when($this->getFilter('end_date'), fn ($query, $eDate) => $query->whereDate('created_at', "<=", $eDate))
            ->orderBy('created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('User'), 'user.name')->searchable()->sortable(),
            Column::make(__('Product'), 'product.name')->searchable()->sortable(),
            Column::make(__('Rating'))->sortable(),
            Column::make(__('Review'))->format(function ($value, $column, $row) {
                return view('components.table.custom', $data = [
                    "value" => "" . $row->review . " ",
                ]);
            }),
            Column::make(__('Created At'), 'formatted_date'),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.delete', $data = [
                    "model" => $row,
                ]);
            }),
        ];
    }


}
