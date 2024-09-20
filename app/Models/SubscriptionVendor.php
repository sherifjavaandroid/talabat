<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kirschbaum\PowerJoins\PowerJoins;

class SubscriptionVendor extends NoDeleteBaseModel
{
    use HasFactory;
    use PowerJoins;

    public $table = "subscription_vendor";
    public $timestamps = false;

    protected $appends = [
        'expired'
    ];


    public function scopeActive($query)
    {
        return $query->whereDate('expires_at', ">", \Carbon\Carbon::now())->where('status', "successful");
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getExpiredAttribute()
    {

        if ($this->expires_at > \Carbon\Carbon::now() && $this->status == "successful") {
            return false;
        }
        return true;
    }
}
