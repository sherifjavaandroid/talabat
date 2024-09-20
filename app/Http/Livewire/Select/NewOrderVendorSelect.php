<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\Vendor;

class NewOrderVendorSelect extends BaseLivewireSelect
{

    public function options($searchTerm = null): Collection
    {
        return Vendor::where('name', 'like', '%' . $searchTerm . '%')
            ->when(\Auth::user()->hasRole('manager'), function ($query) {
                return $query->where('id', \Auth::user()->vendor_id);
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
        $this->emitUp('vendorUpdated', $value);
        $model = Vendor::find($value);
        return [
            'value' => $model->id,
            'description' => $model->name
        ];
    }
}
