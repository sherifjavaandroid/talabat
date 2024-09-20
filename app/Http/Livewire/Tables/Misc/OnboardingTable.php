<?php

namespace App\Http\Livewire\Tables\Misc;

use App\Http\Livewire\Tables\OrderingBaseDataTableComponent;
use App\Models\Onboarding;
use Rappasoft\LaravelLivewireTables\Views\Column;

class OnboardingTable extends OrderingBaseDataTableComponent
{

    public $model = Onboarding::class;
    public $header_view = 'components.buttons.new';


    public function query()
    {
        return Onboarding::query();
    }

    public function columns(): array
    {
        $columns = [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Title'), 'title')->searchable(),
            Column::make(__('Description'), 'description')->addClass('break-all w-64 md:w-3/12')->searchable(),
            Column::make(__('Type'), 'type')->searchable(),
            Column::make(__('Image'))->format(function ($value, $column, $row) {
                return view('components.table.image_md', $data = [
                    "model" => $row
                ]);
            }),
            Column::make(__('Active'))->format(function ($value, $column, $row) {
                return view('components.table.active', $data = [
                    "model" => $row
                ]);
            }),

            Column::make(__('Created At'), 'formatted_date'),

        ];


        if (app()->environment('production')) {
            $columns[] = Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.actions', $data = [
                    "model" => $row
                ]);
            });
        }
        //
        return $columns;
    }
}