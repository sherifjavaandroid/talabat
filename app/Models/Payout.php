<?php

namespace App\Models;


class Payout extends BaseModel
{

    protected $fillable = [
        'amount',
        'earning_id',
        'payment_account_id',
        'payment_method_id',
        'note'
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function earning()
    {
        return $this->belongsTo('App\Models\Earning', 'earning_id', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo('App\Models\PaymentMethod', 'payment_method_id', 'id');
    }

    public function payment_account()
    {
        return $this->belongsTo('App\Models\PaymentAccount', 'payment_account_id', 'id');
    }

}
