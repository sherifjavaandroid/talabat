<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\Product;

class MultipleProductSelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {

        $vendorTypeId = $this->getDependingValue('vendor_type_id') ?? "";
        $vendorId = $this->getDependingValue('vendor_id') ?? "";

        if ($this->hasDependency('vendor_type_id') && empty($vendorTypeId)) {
            return collect();
        }

        if ($this->hasDependency('vendor_id') && empty($vendorId)) {
            return collect();
        }


        return Product::where('name', 'like', '%' . $searchTerm . '%')
            ->when($vendorTypeId, function ($query) use ($vendorTypeId) {
                return $query->whereHas('vendor', function ($query) use ($vendorTypeId) {
                    return $query->where('vendor_type_id', $vendorTypeId);
                });
            })
            ->when($vendorId, function ($query) use ($vendorId) {
                return $query->where('vendor_id', $vendorId);
            })
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
        if ($value != null) {
            $this->selectValue(null);
        }
        return [
            'value' =>  "",
            'description' => "",
        ];
    }
}
