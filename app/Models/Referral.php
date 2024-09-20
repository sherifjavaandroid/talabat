<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends NoDeleteBaseModel
{
    use HasFactory;

    public function scopeMine($query)
    {

        return $query->when(\Auth::user()->hasRole('city-admin'), function ($query) {
            return $query->whereHas('referringUser', function ($query) {
                return $query->where('creator_id', \Auth::id());
            })->orWhereHas('referredUser', function ($query) {
                return $query->where('creator_id', \Auth::id());
            });
        });
    }

    public function referringUser()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function referredUser()
    {
        return $this->belongsTo('App\Models\User', 'referred_user_id', 'id');
    }
}
