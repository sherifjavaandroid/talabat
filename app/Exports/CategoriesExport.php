<?php

namespace App\Exports;

use App\Models\Category;

class CategoriesExport extends BaseExport
{
    /**
    * @return \Illuminate\Support\Collection
    */


    public function collection()
    {
        return Category::all();
    }

    
}
