<?php

namespace App\Exports;

use App\Models\Service;

class ServicesExport extends BaseExport
{
    /**
    * @return \Illuminate\Support\Collection
    */


    public function collection()
    {
        return Service::all();
    }
}
