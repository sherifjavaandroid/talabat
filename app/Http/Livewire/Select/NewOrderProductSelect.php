<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\Product;

class NewOrderProductSelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {

        $vendorId = $this->getDependingValue('vendor_id') ?? 0;
        return Product::where('name', 'like', '%' . $searchTerm . '%')
            ->where('vendor_id', $vendorId)
            ->limit(20)
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
        if ($value != null) {
            $this->emitUp('productsUpdated', $value);
            $this->selectValue(null);
        }
        return [
            'value' => "",
            'description' => "",
        ];
    }
}
