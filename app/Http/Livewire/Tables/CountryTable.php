<?php

namespace App\Http\Livewire\Tables;

use App\Models\Country;
use App\Services\GeoBoundaryService;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CountryTable extends BaseDataTableComponent
{

    public $model = Country::class;
    public $per_page = 20;


    public function query()
    {
        return Country::query();
    }

    public function columns(): array
    {
        return [
            $this->indexColumn(),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
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

    //
    public function syncModelBoundaries($id)
    {
        try {
            $country = Country::find($id);
            $keyword = $country->name;
            $country->boundaries = GeoBoundaryService::getPlaceBoundaries($keyword, "country");
            $country->save();
            $this->showSuccessAlert(__('Boundaries synced successfully'));
        } catch (\Exception $e) {
            $msg = __('Failed to sync boundaries');
            $msg .= ". ";
            $msg .= $e->getMessage();
            $this->showErrorAlert($msg);
        }
    }
}