<?php

namespace App\Models;


class WalletTransaction extends NoDeleteBaseModel
{

    protected $with = ["wallet"];
    protected $fillable = ["amount", "ref", "session_id", "wallet_id", "payment_method_id", "status", "is_credit"];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (empty($model->ref)) {
                $model->ref = "ref_".\Str::random(12);
            }
            // if (empty($model->session_id)) {
            //     $model->session_id = "si_".\Str::random(32);
            // }
        });

        self::updating(function ($model) {
            if (empty($model->ref)) {
                $model->ref = "ref_".\Str::random(12);
            }
            // if (empty($model->session_id)) {
            //     $model->session_id = "si_".\Str::random(32);
            // }
        });
    }


    public function wallet()
    {
        return $this->belongsTo('App\Models\Wallet', 'wallet_id', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo('App\Models\PaymentMethod', 'payment_method_id', 'id');
    }
}
