<?php

namespace App\Models;


class Commission extends NoDeleteBaseModel
{

    protected $fillable = [
        'vendor_commission',
        'driver_commission',
        'admin_commission',
        'order_id',
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id')->withTrashed();
    }
}
