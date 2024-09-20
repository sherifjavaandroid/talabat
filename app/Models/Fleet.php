<?php

namespace App\Models;

class Fleet extends NoDeleteBaseModel
{
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function users(){
        return $this->belongsToMany('App\Models\User');
    }

    public function managers(){
        return $this->belongsToMany('App\Models\User')->role('fleet-manager');
    }

    public function drivers(){
        return $this->belongsToMany('App\Models\User')->role('driver');
    }
}
