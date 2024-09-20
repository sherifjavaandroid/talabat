<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\Subcategory;

class MultipleSubcategorySelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {

        $categoryIds = $this->getDependingValue('category_id') ?? "[]";
        $categoryIds = json_decode($categoryIds, true);

        //
        return Subcategory::whereIn('category_id', $categoryIds)
            ->when($searchTerm, function ($query, $searchTerm) {
                return $query->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->limit(10)
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
