<?php

namespace App\Http\Livewire\Tables;

use App\Models\OptionGroup;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Support\Facades\Auth;

class OptionsGroupTable extends OrderingBaseDataTableComponent
{

    public $model = OptionGroup::class;


    public function query()
    {

        if (Auth::user()->hasRole('admin')) {
            return OptionGroup::query();
        } else {
            return OptionGroup::where('vendor_id', Auth::user()->vendor_id);
        }
    }

    public function columns(): array
    {

        $columns = [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            Column::make(__('Multiple'))->format(function ($value, $column, $row) {
                return view($actionView ?? 'components.table.multiple', $data = [
                    "model" => $row
                ]);
            }),
            Column::make(__('Max Options'), 'max_options'),
            Column::make(__('Created At'), 'formatted_date'),
            $this->actionsColumn(),
        ];

        //
        // if ($this->canManage) {
        //     array_push(
        //         $columns,
        //         Column::make('Actions')->view('components.buttons.actions')
        //     );
        // }
        return $columns;
    }
}
