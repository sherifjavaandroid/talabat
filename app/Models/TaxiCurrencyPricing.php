<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxiCurrencyPricing extends Model
{
    use HasFactory;

    public function currency()
    {
        return $this->belongsTo(Currency::class);
        // return $this->belongsTo('App\Models\CarModel', 'car_model_id', 'id');
    }


    public function vehicle_type()
    {
        return $this->belongsTo(VehicleType::class);
    }

}
