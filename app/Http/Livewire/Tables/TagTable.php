<?php

namespace App\Http\Livewire\Tables;

use App\Models\Tag;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TagTable extends OrderingBaseDataTableComponent
{

    public $model = Tag::class;
    public $header_view = 'components.buttons.new';

    public function query()
    {
        return Tag::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            Column::make(__('Type'), 'vendor_type.name'),
            Column::make(__('Actions'))->format(function ($value, $column, $row) {
                return view('components.buttons.tag_actions', $data = [
                    "model" => $row
                ]);
            })
        ];
    }
    
}
