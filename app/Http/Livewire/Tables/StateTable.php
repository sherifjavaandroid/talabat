<?php

namespace App\Http\Livewire\Tables;

use App\Models\State;
use App\Services\GeoBoundaryService;
use Rappasoft\LaravelLivewireTables\Views\Column;

class StateTable extends BaseDataTableComponent
{

    public $model = State::class;
    public $per_page = 50;

    public function query()
    {
        return State::with('country');
    }

    public function columns(): array
    {
        return [
            $this->indexColumn(),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            Column::make(__('Country'), "country.name")->searchable(),
            //has boundaries column
            Column::make(__('Has Boundaries'), 'boundaries')->format(function ($value, $column, $row) {
                return view('components.table.bool', $data = [
                    "model" => $row,
                    'isTrue' => $value != null,
                ]);
            })->addClass('w-48')->sortable(),
            $this->actionsColumn('components.buttons.geodata_actions')->addClass('w-4/12'),

        ];
    }


    public function syncModelBoundaries($id)
    {
        try {
            $state = State::find($id);
            $keyword = $state->name . ', ' . $state->country->name;
            $state->boundaries = GeoBoundaryService::getPlaceBoundaries($keyword, "state");
            $state->save();
            $this->showSuccessAlert(__('Boundaries synced successfully'));
        } catch (\Exception $e) {
            $msg = __('Failed to sync boundaries');
            $msg .= ". ";
            $msg .= $e->getMessage();
            $this->showErrorAlert($msg);
        }
    }
}