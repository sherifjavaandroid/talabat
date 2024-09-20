<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\Vendor;
use Carbon\Carbon;

class VendorScheduleDateSelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {

        $vendorId = $this->getDependingValue('vendor_id') ?? 0;
        $slots = [];
        $vendor = Vendor::find($vendorId);

        //
        if ($vendor == null) {
            return collect($slots);
        }
        //
        $days = $vendor->days->pluck('name')->toArray();
        //
        if (!empty($days)) {
            //max order schedule days
            $daysCount = setting('maxScheduledDay', 5) + 1;
            //
            for ($i = 0; $i < $daysCount; $i++) {
                $date = Carbon::now()->addDays($i);
                $dateDayName = $date->format('l d M, Y');
                $slots[] = [
                    'value' => $date->format('Y-m-d'),
                    'description' => $dateDayName,
                ];
            }

            //create collection
            $slots = collect($slots);
        }

        return $slots;
    }


    public function selectedOption($value)
    {
        //carbon string to date
        $valueFormatted = Carbon::parse($value);
        $description = $valueFormatted->format('l d M, Y');
        return [
            'value' => $value,
            'description' => $description,
        ];
    }
}
