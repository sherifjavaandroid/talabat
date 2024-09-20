<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AutoAssignment extends Model
{
    
    protected $with = ['order', 'driver'];   
    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\User', 'driver_id', 'id');
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at != null ? $this->created_at->format('d M Y \\a\\t H:i a') : '';
    }
}
