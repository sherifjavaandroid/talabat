<?php

namespace App\Models;


class LoyaltyPoint extends BaseModel
{

    protected $fillable = [
        'points',
        'user_id',
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id')->withTrashed();
    }


}
