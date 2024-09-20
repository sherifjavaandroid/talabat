<?php

namespace App\Models;


class Payment extends NoDeleteBaseModel
{


    //order relation
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
