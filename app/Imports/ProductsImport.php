<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ProductsImport implements OnEachRow, WithHeadingRow
{

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();
        if (!empty($row["name"])) {
            $product = Product::updateOrCreate(
                ['id' => $row["id"]],
                $row
            );

            //
            if ($row["menus_id"] != null) {
                $menusIds = explode(",", $row["menus_id"]);
                $product->menus()->sync($menusIds);
            }

            if ($row["category_ids"] != null) {
                $categoryIds = explode(",", $row["category_ids"]);
                $product->categories()->sync($categoryIds);
            }

            if ($row["sub_category_ids"] != null) {
                $subCategoryIds = explode(",", $row["sub_category_ids"]);
                $product->sub_categories()->sync($subCategoryIds);
            }

            if ($row["option_ids"] != null) {
                $optionIds = explode(",", $row["option_ids"]);
                $product->options()->sync($optionIds);
            }

            if ($row["image"] != null && !empty($row["image"])) {

                $product->clearMediaCollection();
                $product->addMediaFromUrl($row["image"])->toMediaCollection('default');

            }
        }
    }
}
