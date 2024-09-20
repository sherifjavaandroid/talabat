<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\ModelStatus\Status;
use DateTimeInterface;


class OrderStatus extends Status
{
    protected $guarded = [];

    protected $table = 'statuses';
    protected $casts = [
        'model_id' => 'int',
    ];
    // protected $appends = ["created_at"];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    // public function getCreatedAtAttribute($value){
    //     return \Carbon\Carbon::parse($value)->timezone(setting('timeZone',"UTC"));
    // }

    // public function getCleanUpdatedAtAttribute($value){
    //     return \Carbon\Carbon::parse($value)->timezone(setting('timeZone',"UTC"));
    // }
}
