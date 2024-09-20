<?php

namespace App\Exports;

use App\Models\Vendor;
class VendorsExport extends BaseExport
{
    /**
    * @return \Illuminate\Support\Collection
    */


    public function collection()
    {
        return Vendor::all();
    }
}
