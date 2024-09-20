<?php

namespace App\Models;

class OrderProduct extends NoDeleteBaseModel
{

    protected $casts = [
        'id' => 'int',
        'quantity' => 'int',
        'order_id' => 'int',
        'product_id' => 'int',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id')->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id')->withTrashed();
    }
}
