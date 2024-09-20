<?php

namespace App\Imports;

use App\Models\Subcategory;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class SubcategoriesImport implements OnEachRow, WithHeadingRow
{

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();
        if (!empty($row["name"])) {
           
            $subcategory = Subcategory::updateOrCreate(
                ['id' => $row["id"]],
                $row
            );

            if ($row["image"] != null && !empty($row["image"])) {

                $subcategory->clearMediaCollection();
                $subcategory->addMediaFromUrl($row["image"])->toMediaCollection();
            }
        }
    }
}
