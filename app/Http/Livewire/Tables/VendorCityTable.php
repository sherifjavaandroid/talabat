<?php

namespace App\Http\Livewire\Tables;

use App\Models\CityVendor;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Support\Facades\Auth;

class VendorCityTable extends BaseDataTableComponent
{

    public $model = CityVendor::class;
    public $header_view = 'components.buttons.new';

    public function query()
    {
        return CityVendor::with('city.state.country', 'vendor')->where('vendor_id', Auth::user()->vendor_id);
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->sortable(),
            Column::make(__('Name'), 'city.name')->searchable()->sortable(),
            Column::make(__('State'), "city.state.name")->searchable(),
            Column::make(__('Country'), "city.state.country.name")->searchable(),
            $this->activeColumn(),
            $this->actionsColumn('components.buttons.edit'),
        ];
    }
}
