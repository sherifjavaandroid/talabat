<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\HasTranslations;

class Coupon extends BaseModel
{
    use HasTranslations;
    public $translatable = ["description"];
    protected $appends = ['formatted_expires_on', 'use_left', 'expired', 'photo'];

    protected $casts = [
        'for_delivery' => 'boolean',
    ];

    public function getFormattedExpiresOnAttribute()
    {
        return Carbon::parse($this->expires_on)->format('d M Y');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }

    public function vendors()
    {
        return $this->belongsToMany('App\Models\Vendor');
    }

    public function vendor_type()
    {
        return $this->belongsTo('App\Models\VendorType');
    }



    public function getUseLeftAttribute()
    {

        if (empty($this->times)) {
            return 1;
        }

        $couponUses = CouponUser::where([
            'coupon_id' => $this->id,
            'user_id' => auth('api')->user()->id ?? null,
        ])->get()->count();
        //
        return $this->times - $couponUses;
    }

    public function getExpiredAttribute()
    {

        return $this->expires_on < now();
    }
}
