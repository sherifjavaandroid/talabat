<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    
    public function car_make()
    {
        return $this->belongsTo('App\Models\CarMake', 'car_make_id', 'id');
    }
}
