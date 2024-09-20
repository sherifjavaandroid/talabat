<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\Vendor;

class VendorSelect extends BaseLivewireSelect
{

    public function options($searchTerm = null): Collection
    {
        $vendorTypeId = $this->getDependingValue('vendor_type_id') ?? "";

        //if has vendor_type_id dependency but no value, return empty collection
        if ($this->hasDependency('vendor_type_id') && empty($vendorTypeId)) {
            return collect();
        }
        //
        return Vendor::where('name', 'like', '%' . $searchTerm . '%')
            ->when(\Auth::user()->hasRole('manager'), function ($query) {
                return $query->where('id', \Auth::user()->vendor_id);
            })
            ->when(!empty($vendorTypeId), function ($query) use ($vendorTypeId) {
                return $query->where('vendor_type_id', $vendorTypeId);
            })
            ->limit(10)
            ->get()
            ->map(function ($vendor) {
                return [
                    'value' => $vendor->id,
                    'description' => $vendor->name,
                ];
            });
    }


    public function selectedOption($value)
    {
        $model = Vendor::find($value);
        return [
            'value' => $model->id,
            'description' => $model->name
        ];
    }
}
