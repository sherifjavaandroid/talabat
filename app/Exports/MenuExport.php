<?php

namespace App\Exports;

use App\Models\Menu;

class MenuExport extends BaseExport
{
    /**
    * @return \Illuminate\Support\Collection
    */


    public function collection()
    {
        return Menu::all();
    }
}
