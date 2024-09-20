<?php

namespace App\Http\Livewire\Tables;

use App\Models\StateVendor;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Support\Facades\Auth;

class VendorStateTable extends BaseDataTableComponent
{

    public $model = StateVendor::class;
    public $header_view = 'components.buttons.new';

    public function query()
    {
        return StateVendor::with('state.country', 'vendor')->where('vendor_id', Auth::user()->vendor_id);
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->sortable(),
            Column::make(__('Name'), 'state.name')->searchable()->sortable(),
            Column::make(__('Country'), "state.country.name")->searchable(), $this->activeColumn(),
            $this->actionsColumn('components.buttons.edit'),
        ];
    }
}
