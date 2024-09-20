<?php

namespace App\Http\Livewire\Tables;


use App\Models\PackageType;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PackageTypeTable extends OrderingBaseDataTableComponent
{

    public $model = PackageType::class;

    public function query()
    {
        return PackageType::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'),"id")->searchable()->sortable(),
            Column::make(__('Name'),'name')->searchable(),
            Column::make(__('Stops verification'),'driver_verify_stops'),
            Column::make(__('Description')),
            $this->smImageColumn(),
            $this->activeColumn(),
            Column::make(__('Created At'), 'formatted_date'),
            $this->actionsColumn(),
        ];
    }
}
