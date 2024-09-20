<?php

namespace App\Http\Livewire\Tables;

use App\Models\Subcategory;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SubCategoryTable extends OrderingBaseDataTableComponent
{

    public $model = Subcategory::class;


    public function query()
    {
        return Subcategory::with('category');
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            $this->xsImageColumn(),
            Column::make(__('Category'), 'category.name')->sortable(
                function ($query, $direction) {
                    //order by category name using join
                    return $query->join('categories', 'categories.id', '=', 'subcategories.category_id')
                        ->orderBy('categories.name', $direction);
                }
            ),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            $this->activeColumn(),
            Column::make(__('Created At'), 'formatted_date')->sortable(
                function ($query, $direction) {
                    return $query->orderBy('created_at', $direction);
                }
            ),
            $this->actionsColumn(),
        ];
    }
}
