<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\User;

class EditOrderDriverSelect extends BaseLivewireSelect
{

    public function options($searchTerm = null): Collection
    {
        $hasOwnDrivers = \Auth::user()->vendor && \Auth::user()->vendor->has_own_drivers;
        //
        return User::role('driver')
            ->where('name', 'like', '%' . $searchTerm . '%')
            ->when($hasOwnDrivers, function ($query) {
                return $query->where('vendor_id', \Auth::user()->vendor_id);
            })
            ->limit(10)
            ->get()
            ->map(function ($model) {
                return [
                    'value' => $model->id,
                    'description' => $model->name,
                ];
            });
    }


    public function selectedOption($value)
    {
        $model = User::find($value);
        return [
            'value' => $model->id,
            'description' => $model->name
        ];
    }
}
