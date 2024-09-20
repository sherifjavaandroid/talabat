<?php

namespace App\Models;

class Subscription extends NoDeleteBaseModel
{


    public function vendors()
    {
        return $this->hasMany(SubscriptionVendor::class);
    }

    public function successful_sub()
    {
        return $this->hasMany(SubscriptionVendor::class)->where('status','successful');
    }

    public function pending_sub()
    {
        return $this->hasMany(SubscriptionVendor::class)->where('status','pending');
    }
    
    public function failed_sub()
    {
        return $this->hasMany(SubscriptionVendor::class)->where('status','failed');
    }

}
