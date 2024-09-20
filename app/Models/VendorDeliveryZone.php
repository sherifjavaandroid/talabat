<?php

namespace App\Models;


class VendorDeliveryZone extends NoDeleteBaseModel
{


    public $table = "delivery_zone_vendor";
    public $timestamps = false;

    protected $fillable = [
        'vendor_id',
        'delivery_zone_id',
    ];

    protected $with = [
        'delivery_zone'
    ];

    public function delivery_zone()
    {
        return $this->belongsTo(DeliveryZone::class);
    }
    
}
