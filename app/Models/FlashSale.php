<?php

namespace App\Models;


class FlashSale extends NoDeleteBaseModel
{


    public function items()
    {
        return $this->hasMany('App\Models\FlashSaleItem');
    }

    public function vendor_type()
    {
        return $this->belongsTo('App\Models\VendorType');
    }

    public function scopeNotExpired($query){
        return $query->where('expires_at', '>', \Carbon\Carbon::now());
    }

}
