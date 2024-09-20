<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\Subcategory;

class SubCategorySelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {

        $categoryId = $this->getDependingValue('category_id') ?? "";
        //if has vendor_type_id and vendor_id dependency but no value, return empty collection
        if ($this->hasDependency('category_id') && empty($categoryId)) {
            return collect();
        }

        //
        return Subcategory::where('name', 'like', '%' . $searchTerm . '%')
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
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
        $model = Subcategory::find($value);
        return [
            'value' =>  $model->id,
            'description' => $model->name,
        ];
    }
}
