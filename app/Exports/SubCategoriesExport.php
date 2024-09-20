<?php

namespace App\Exports;

use App\Models\Subcategory;

class SubCategoriesExport extends BaseExport
{
    /**
    * @return \Illuminate\Support\Collection
    */


    public function collection()
    {
        return Subcategory::all();
    }
}
