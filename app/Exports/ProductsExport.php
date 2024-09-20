<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Product;
use Illuminate\Support\Arr;

class ProductsExport implements FromQuery, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public $appendColumns = ["menus_id", "category_ids", "sub_category_ids", "option_ids"];
    public $expectColumns = ["option_groups"];

    public function headings(): array
    {
        return array_merge(array_keys(Arr::except($this->query()->first()->toArray(), $this->expectColumns)), $this->appendColumns);
    }

    public function query()
    {
        return Product::without("vendor", 'option_groups');
    }

    public function map($product): array
    {

        $data = $product->toArray();
        $data = Arr::except($data, $this->expectColumns);

        $menusIds = "";
        if ($product->menus()->exists()) {
            $menusIds = implode(",", $product->menus->pluck("id")->toArray());
        }

        $categoryIds = "";
        if ($product->categories()->exists()) {
            $categoryIds = implode(",", $product->categories->pluck("id")->toArray());
        }

        $subCategoryIds = "";
        if ($product->sub_categories()->exists()) {
            $subCategoryIds =  implode(",", $product->sub_categories->pluck("id")->toArray());
        }

        $optionIds = "";
        if ($product->options()->exists()) {
            $optionIds =  implode(",", $product->options->pluck("id")->toArray());
        }


        return array_merge($data, [
            "menus_id" => $menusIds,
            "category_ids" => $categoryIds,
            "sub_category_ids" => $subCategoryIds,
            "option_ids" => $optionIds,
        ]);
    }
}
