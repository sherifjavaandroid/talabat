<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\Tag;

class TagSelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {
        $vendorTypeId = $this->getDependingValue('vendor_type_id') ?? "";
        return Tag::where('name', 'like', '%' . $searchTerm . '%')
            ->when(!empty($vendorTypeId), function ($query) use ($vendorTypeId) {
                $query->whereHas('vendor_type', function ($query) use ($vendorTypeId) {
                    $query->where('vendor_types.id', $vendorTypeId);
                });
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
            $this->searchTerm = null;
        }
        return [
            'value' =>  "",
            'description' => "",
        ];
    }
}
