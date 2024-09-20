<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\Category;

class CategorySelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {

        $vendorTypeId = $this->getDependingValue('vendor_type_id') ?? "";
        $vendorId = $this->getDependingValue('vendor_id') ?? "";

        //if has vendor_type_id and vendor_id dependency but no value, return empty collection
        if (($this->hasDependency('vendor_type_id') && empty($vendorTypeId)) || ($this->hasDependency('vendor_id') && empty($vendorId))) {
            return collect();
        }

        //
        return Category::where('name', 'like', '%' . $searchTerm . '%')
            ->when($vendorId, function ($query) use ($vendorId) {
                return $query->whereHas('vendors', function ($query) use ($vendorId) {
                    return $query->where('id', $vendorId);
                });
            })
            ->when($vendorTypeId, function ($query) use ($vendorTypeId) {
                return $query->where('vendor_type_id', $vendorTypeId);
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
        $model = Category::find($value);
        return [
            'value' =>  $model->id,
            'description' => $model->name,
        ];
    }
}
