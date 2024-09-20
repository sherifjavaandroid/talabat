<?php

namespace App\Traits;

use Carbon\Carbon;

trait VendorSlotTrait
{


    public function getSlotTimes($schuldeInfo, $index, $dayTiming, $maxScheduledTime, $currentTime)
    {
        //minutes will be use
        $slotIntervalInMins = (int) setting("vendor_slot_interval", 60);
        $maxScheduledTimeInMinutes = 60 * ((int) $maxScheduledTime);

        //
        $minsDiff = $this->calculateDiffInMins($dayTiming->pivot->open, $dayTiming->pivot->close);

        $minsDiff -= $maxScheduledTimeInMinutes;
        //number of loop time
        $loopTimes = $minsDiff / $slotIntervalInMins;
        $loopTimes = (int) floor($loopTimes);

        //
        $dateTiming = [];
        for ($j = 1; $j <= $loopTimes; $j++) {
            $newMinutes = $j * $slotIntervalInMins;
            $time = $this->carbonFromTime($dayTiming->pivot->open)->addMinutes($newMinutes)->format('H:i:s');
            //remove time on today
            $minTime = $this->carbonFromTime($currentTime)->addMinutes($maxScheduledTimeInMinutes)->format('H:i:s');
            if ($index == 0 && $minTime <= $time) {
                array_push($dateTiming, $time);
            } else if ($index > 0) {
                array_push($dateTiming, $time);
            }
        }

        $schuldeInfo["times"] = $dateTiming;
        //
        return $schuldeInfo;
    }


    //MISC.
    public function calculateDiffInHours($from, $to)
    {
        $from = Carbon::createFromFormat('H:s:i', $from);
        $to = Carbon::createFromFormat('H:s:i', $to);
        return $to->diffInHours($from) ?? 0;
    }
    public function calculateDiffInMins($from, $to)
    {
        $from = Carbon::createFromFormat('H:s:i', $from);
        $to = Carbon::createFromFormat('H:s:i', $to);
        return $to->diffInMinutes($from) ?? 0;
    }
    public function carbonFromTime($time)
    {
        return Carbon::createFromFormat('H:s:i', $time);
    }
}
