<?php

namespace App\Models;

use Illuminate\Support\Str;

class PaymentMethod extends BaseModel
{

    protected $fillable = ["slug", "name", "is_active", "is_cash"];
    protected $hidden = ["secret_key", "public_key", "hash_key"];
    protected $casts = [
        'id' => 'integer',
        'is_active' => 'integer',
        'is_cash' => 'integer',
        'use_taxi' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->slug = Str::slug($model->name);
            $model->save();
        });
    }


    public function scopeTopUp($query)
    {
        return $query->where('is_cash', 0)->where('use_wallet', 1);
    }

    public function scopeSub($query)
    {
        // return $query->where('is_cash', 0)->where('slug', "!=", "wallet");
        return $query->where(function ($query) {
            $query->where('is_cash', 0)->orWhere('slug',  "wallet");
        });
    }


    //attribute getters
    public function getWehbookLinkAttribute()
    {
        return route('api.payment.webhook', $this->webhook_hash ?? "");
    }
}