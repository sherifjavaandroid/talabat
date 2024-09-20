<?php

namespace App\Http\Livewire\Tables;

use App\Models\City;
use App\Services\GeoBoundaryService;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CityTable extends BaseDataTableComponent
{

    public $model = City::class;
    public $per_page = 100;

    public function query()
    {
        return City::with('state.country');
    }

    public function columns(): array
    {
        return [
            $this->indexColumn(),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            Column::make(__('State'), "state.name")->searchable()->sortable(),
            Column::make(__('Country'), "state.country.name")->searchable()->sortable(),
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
            $city = City::find($id);
            $keyword = $city->name . ', ' . $city->state->name . ', ' . $city->state->country->name;
            $city->boundaries = GeoBoundaryService::getPlaceBoundaries($keyword, "city");
            $city->save();
            $this->showSuccessAlert(__('Boundaries synced successfully'));
        } catch (\Exception $e) {
            $msg = __('Failed to sync boundaries');
            $msg .= ". ";
            $msg .= $e->getMessage();
            $this->showErrorAlert($msg);
        }
    }
}