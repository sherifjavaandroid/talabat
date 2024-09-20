<?php

namespace App\Traits;

use App\Models\ProductTiming;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\Day;

trait ProductAttributeTrait
{
    public $digitalFilePath = "secure/product/files";

    public function clearDigitalFiles()
    {
        \Storage::deleteDirectory("{$this->digitalFilePath}/{$this->id}");
    }
    //digital product path
    public function saveDigitalFile($digitalFile)
    {
        \Storage::putFile("{$this->digitalFilePath}/{$this->id}", $digitalFile);
    }

    public function getDigitalFilesAttribute()
    {
        $files = \Storage::allFiles("{$this->digitalFilePath}/{$this->id}");
        $modFiles = [];
        $auth = "";
        // if (!\Request::wantsJson()) {
        $auth = \Crypt::encrypt([
            "user_id" => \Auth::id(),
        ]);
        // }

        foreach ($files as $key => $file) {
            $modFiles[] = json_decode(
                json_encode([
                    "name" => array_reverse(explode("/", $file))[0],
                    "size" => Storage::size($file),
                    "link" => route('digital.download', ["id" => $this->id, "auth" => $auth]),
                ])
            );
        }
        return $modFiles;
    }



    //product timing relation
    public function timings()
    {
        return $this->hasMany(ProductTiming::class);
    }

    //add scope to fetch product with timings for current day and time
    public function scopeCurrentlyOpen($query)
    {

        $day = now()->format('l');
        //get day id, with sunday as 1
        $dayId = Day::where('name', $day)->first()->id;
        $time = now()->format('H:i:s');
        ///where has timing or where timing is empty
        return $query->where(
            function ($query) use ($dayId, $time) {
                $query->whereHas('timings', function ($query) use ($dayId, $time) {
                    $query->where('day_id', $dayId)
                        ->where('start_time', '<=', $time)
                        ->where('end_time', '>=', $time);
                })->orWhereDoesntHave('timings');
            }
        );
    }

    //add scope to fetch delivered order
    public function successful_sales()
    {
        // return $this->hasMany('App\Models\OrderProduct', 'product_id', 'id');
        return $this->hasMany('App\Models\OrderProduct')->whereHas("order", function ($query) {
            return $query->where('payment_status', "successful")
                ->currentStatus("delivered");
        });
    }
}
