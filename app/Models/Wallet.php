<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class Wallet extends NoDeleteBaseModel
{

    protected $with = ['user'];
    protected $fillable = ["balance", "user_id"];
    protected $casts = [
        'balance' => 'double',
    ];

    public function scopeMine($query)
    {
        return $query->where('user_id', Auth::id());
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function saveWalletTransaction($amount, $reason, $isCredit = true, $ref = null, $status = "successful")
    {
        $walletTransaction = new WalletTransaction();
        $walletTransaction->amount = $amount;
        $walletTransaction->wallet_id = $this->id;
        $walletTransaction->is_credit = $isCredit;
        $walletTransaction->reason = $reason ?? "";
        $walletTransaction->ref = $ref;
        $walletTransaction->status = $status;
        $walletTransaction->save();
        return $walletTransaction;
    }
}
