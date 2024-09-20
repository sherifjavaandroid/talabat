<?php

namespace App\Imports;

use App\Models\Service;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ServicesImport implements OnEachRow, WithHeadingRow
{

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();
        if (!empty($row["name"])) {
            $service = Service::updateOrCreate(
                ['id' => $row["id"]],
                $row
            );

            if ($row["photo"] != null && !empty($row["photo"])) {

                $service->clearMediaCollection();
                $service->addMediaFromUrl($row["photo"])->toMediaCollection('default');

            }
        }
    }
}
