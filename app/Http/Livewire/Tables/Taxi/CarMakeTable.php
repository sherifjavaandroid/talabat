<?php

namespace App\Http\Livewire\Tables\Taxi;

use App\Http\Livewire\Tables\BaseTableComponent;
use App\Models\CarMake;
use Kdion4891\LaravelLivewireTables\Column;

class CarMakeTable extends BaseTableComponent
{

    public $model = CarMake::class;
    public $header_view = 'components.buttons.new';

    public function query()
    {
        return CarMake::query();
    }

    public function columns()
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            Column::make(__('Name'),'name')->searchable(),
            Column::make(__('Actions'))->view('components.buttons.edit'),
        ];
    }
}
