<?php

namespace App\Models;


class OrderService extends NoDeleteBaseModel
{

    protected $with = ['service'];

    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id')->withTrashed();
    }


}

