<?php

namespace App\Models;


class VehicleType extends NoDeleteBaseModel
{
    protected $appends = ['formatted_date', 'photo'];

    public static function boot() {
        parent::boot();
    
        //while creating/inserting item into db  
        static::creating(function ($model) {
            $model->slug = \Str::slug($model->name);
        });
    
        
    }
}
