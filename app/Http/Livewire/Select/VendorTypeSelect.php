<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\VendorType;

class VendorTypeSelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {
        return VendorType::where('name', 'like', '%' . $searchTerm . '%')
            ->where('is_active', 1)
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
            'description' =>  VendorType::find($value)->name,
        ];
    }
}
