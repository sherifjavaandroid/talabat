<?php

namespace App\Http\Livewire\Tables;

use App\Models\Category;
use App\Models\VendorType;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class CategoryTable extends OrderingBaseDataTableComponent
{

    public $model = Category::class;

    public function filters(): array
    {
        $vendorTypesFilterArray = [
            '' => __('Any'),
        ];
        $vendorTypes = VendorType::assignable()->get();
        foreach ($vendorTypes as $vendorType) {
            $vendorTypesFilterArray[$vendorType->id] = $vendorType->name;
        }
        return [
            'vendor_type' => Filter::make(__("Vendor Type"))
                ->select($vendorTypesFilterArray),
        ];
    }


    public function query()
    {
        return Category::with('vendor_type')->withCount('sub_categories')
            ->when($this->getFilter('vendor_type'), fn ($query, $vendorTypeId) => $query->where('vendor_type_id', $vendorTypeId));
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            $this->xsImageColumn(),
            Column::make(__('Name'), 'name')->searchable()->sortable(),
            Column::make(__('Vendor Type'), 'vendor_type.name')->sortable(function ($query, $direction) {
                //order by category name using join
                return $query->join('vendor_types', 'vendor_types.id', '=', 'categories.vendor_type_id')
                    ->orderBy('vendor_types.name', $direction);
            }),
            Column::make(__('No Subcategories'), 'sub_categories_count')->sortable(),
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
