<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class EarningReport extends NoDeleteBaseModel
{
    use HasFactory;

    protected $fillable = [
        'earning_id',
        'order_id',
        'earning',
        'commission',
        'balance',
        'rate',
    ];


    public function earnings()
    {
        return $this->belongsTo('App\Models\Earning', 'earning_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }

}
