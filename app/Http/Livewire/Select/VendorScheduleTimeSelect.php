<?php

namespace App\Http\Livewire\Select;

use Illuminate\Support\Collection;
use App\Models\Vendor;
use App\Models\Day;
use Carbon\Carbon;

class VendorScheduleTimeSelect extends BaseLivewireSelect
{
    public function options($searchTerm = null): Collection
    {

        $slots = [];
        //
        $vendorId = $this->getDependingValue('vendor_id') ?? 0;
        $vendor = Vendor::find($vendorId);
        $date = $this->getDependingValue('schedule_date') ?? 0;

        //
        if ($date == null || $vendor == null) {
            return collect($slots);
        }



        $date = Carbon::parse($date);
        $dateDayName = $date->format('l');
        $days = $vendor->days->pluck('name')->toArray();
        $daysTiming = $vendor->days;
        //
        try {

            $maxScheduledTime = setting('maxScheduledTime', 2);
            $currentTime = now()->format('H:s:i');
            //
            //times
            $dayTimingIndex = array_search($dateDayName, $days);
            $dayTiming = $daysTiming[$dayTimingIndex];

            $hoursdIFF = $this->calculateDiffInHours($dayTiming->pivot->open, $dayTiming->pivot->close);
            $hoursdIFF -= $maxScheduledTime;
            if (!empty($hoursdIFF)) {

                for ($j = 1; $j < $hoursdIFF; $j++) {
                    //
                    $time = $this->carbonFromTime($dayTiming->pivot->open)->addHours($j);
                    //remove time on today
                    $minTime = $this->carbonFromTime($currentTime)->addHours($maxScheduledTime)->format('H:s:i');

                    //if date is today and time is less than min time
                    if ($date->isToday() && $time->format('H:s:i') < $minTime) {
                        continue;
                    }


                    $slots[] = [
                        'value' => $time->format('H:s:i'),
                        'description' => $time->format('h:i A'),
                    ];
                }
            }
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }

        return collect($slots);
    }


    public function selectedOption($value)
    {
        return [
            'value' => $value,
            'description' => $value,
        ];
    }





    // OTHER FUNCTIONS
    public function calculateDiffInHours($from, $to)
    {
        $from = Carbon::createFromFormat('H:s:i', $from);
        $to = Carbon::createFromFormat('H:s:i', $to);
        return $to->diffInHours($from) ?? 0;
    }

    public function carbonFromTime($time)
    {
        return Carbon::createFromFormat('H:s:i', $time);
    }
}
