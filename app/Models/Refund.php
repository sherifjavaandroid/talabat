<?php

namespace App\Models;

class Refund extends NoDeleteBaseModel
{


    protected $fillable = [
        'order_id',
        'status',
    ];

    public $with = ["order"];

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id')->withTrashed();
    }
}