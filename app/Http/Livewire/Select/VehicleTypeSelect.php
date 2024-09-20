<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\VehicleType;

class VehicleTypeSelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {
        return VehicleType::where('name', 'like', '%' . $searchTerm . '%')
            ->limit(20)
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
        return [
            'value' => $value,
            'description' =>  VehicleType::find($value)->name,
        ];
    }
}
