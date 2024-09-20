<?php

namespace App\Http\Livewire\Tables;

use App\Models\AutoAssignment;
use App\Models\User;
use Rappasoft\LaravelLivewireTables\Views\Column;

use Illuminate\Support\Facades\Auth;


class AutoAssignmentTable extends BaseDataTableComponent
{


    
    public $per_page = 10;



    public function query()
    {

        $user = User::find(Auth::id());
        if ($user->hasRole('admin')) {
            return AutoAssignment::orderBy('id', "DESC");
        } else if ($user->hasRole('city-admin')) {
            return AutoAssignment::with('order.vendor', function ($query) {
                return $query->where('creator_id', Auth::id());
            })->fullData()->orderBy('id', "DESC");
        }
    }

    public function columns(): array
    {

        $columns = [
            Column::make(__('ID'),'id')->searchable()->sortable(),
            Column::make(__('Order Code'), 'order.code')->searchable()->sortable(),
            Column::make(__('Driver'), 'driver.name')->searchable()->sortable(),
            Column::make(__('Status'), 'status')->sortable(),
            Column::make(__('Created At'), 'formatted_date'),
        ];
        return $columns;
    }
}
