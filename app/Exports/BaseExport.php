<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BaseExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $appendColumns = [];
    public function collection()
    {
        return [];
    }

    public function headings(): array
    {
        return array_merge( array_keys($this->collection()->first()->toArray()), $this->appendColumns);
    }
}
