<?php

namespace App\Imports;

use App\Models\Vendor;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class VendorsImport implements OnEachRow, WithHeadingRow
{

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();
        if (!empty($row["name"])) {
            $vendor = Vendor::updateOrCreate(
                ['id' => $row["id"]],
                $row
            );

            //
            $categoriesIds = explode(",", $row["categories_id"]);
            $vendor->categories()->sync($categoriesIds);

            if ($row["logo"] != null && !empty($row["logo"])) {

                $vendor->clearMediaCollection("logo");
                $vendor->addMediaFromUrl($row["logo"])->toMediaCollection('logo');

            }
            if ($row["feature_image"] != null && !empty($row["feature_image"])) {

                $vendor->clearMediaCollection("feature_image");
                $vendor->addMediaFromUrl($row["feature_image"])->toMediaCollection('feature_image');

            }
        }
    }
}
