<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\VendorType;

class SalesVendorTypeSelect extends BaseLivewireSelect
{

    //listeners
    public function getListeners()
    {
        return $this->listeners + [
            'initial_vendor_type' => "initialVendorType",
        ];
    }

    public function options($searchTerm = null): Collection
    {
        return VendorType::where('name', 'like', '%' . $searchTerm . '%')
            ->where('is_active', 1)
            ->sales()
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
            'description' =>  VendorType::find($value)->name ?? '',
        ];
    }


    public function initialVendorType($vendor_type_id)
    {
        $this->selectValue($vendor_type_id);
    }
}
