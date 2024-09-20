<?php

namespace App\Http\Livewire\Tables\Misc;

use App\Http\Livewire\Tables\OrderingBaseDataTableComponent;
use App\Models\Faq;
use Rappasoft\LaravelLivewireTables\Views\Column;

class FaqTable extends OrderingBaseDataTableComponent
{

    public $model = Faq::class;
    public $header_view = 'components.buttons.new';


    public function query()
    {
        return Faq::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            Column::make(__('Title'),'title')->searchable(),
            Column::make(__('Active'))->format(function ($value, $column, $row) {
                return view('components.table.active', $data = [
                    "model" => $row
                ]);
            }),

            Column::make(__('Created At'), 'formatted_date'),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.crud_actions', $data = [
                    "model" => $row
                ]);
            }),
        ];
    }
}
