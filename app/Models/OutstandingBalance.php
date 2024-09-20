<?php

namespace App\Models;

class OutstandingBalance extends BaseModel
{
    
    protected $fillable = ["order_id", "user_id"];

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id')->withTrashed();
    }

    
}
