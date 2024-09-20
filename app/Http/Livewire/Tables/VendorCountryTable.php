<?php

namespace App\Http\Livewire\Tables;

use App\Models\CountryVendor;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VendorCountryTable extends BaseDataTableComponent
{

    public $model = CountryVendor::class;

    public function query()
    {
        return CountryVendor::with('country', 'vendor')->where('vendor_id', Auth::user()->vendor_id);
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->sortable(),
            Column::make(__('Name'), 'country.name')->searchable()->sortable(),
            $this->activeColumn(),
            $this->actionsColumn('components.buttons.edit'),
        ];
    }
}
