<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remittance extends Model
{
    use HasFactory;

    protected $appends = ['order_total', 'formatted_updated_date'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }


    public function getOrderTotalAttribute()
    {

        return $this->order->total;
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at != null ? $this->created_at->format('d M Y') : '';
    }

    public function getFormattedUpdatedDateAttribute()
    {
        return $this->updated_at != null ? $this->updated_at->format('d M Y') : '';
    }

}
