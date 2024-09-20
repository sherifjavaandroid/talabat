<?php

namespace App\Models;



class PaymentAccount extends NoDeleteBaseModel
{


    protected $fillable = [
        'name',
        'number',
        'instructions',
        'is_active'
    ];

    public function getTypeAttribute()
    {
        return substr(strrchr($this->accountable_type, "\\"), 1);
    }

    public function accountable()
    {
        return $this->morphTo();
    }

}
