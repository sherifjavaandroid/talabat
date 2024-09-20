<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;

class Vehicle extends NoDeleteBaseModel
{

    protected $with = ['car_model.car_make', 'vehicle_type'];

    protected $casts = [
        'id' => 'integer',
        'verified' => 'boolean',
    ];


    public function scopeAvailable($query)
    {
        if (!Schema::hasColumn('vehicles', 'verified')) {
            return $query->where('is_active', '=', 1);
        }
        return $query->where('is_active', '=', 1)->where('verified', '=', 1);
    }


    public function driver()
    {
        return $this->belongsTo('App\Models\User', 'driver_id', 'id');
    }

    public function car_model()
    {
        return $this->belongsTo('App\Models\CarModel', 'car_model_id', 'id');
    }

    public function vehicle_type()
    {
        return $this->belongsTo('App\Models\VehicleType', 'vehicle_type_id', 'id');
    }

    public function getPhotosAttribute()
    {
        $mediaItems = $this->getMedia('default');
        $photos = [];

        foreach ($mediaItems as $mediaItem) {
            array_push($photos, $mediaItem->getFullUrl());
        }
        return $photos;
    }
}
