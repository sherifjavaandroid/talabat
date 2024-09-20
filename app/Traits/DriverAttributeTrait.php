<?php

namespace App\Traits;

trait DriverAttributeTrait
{

    public function getIsTaxiDriverAttribute()
    {
        if ($this->role_name == "driver") {
            $driverType = $this->driver_type;
            if (empty($driverType)) {
                return $this->vehicle != null;
            } else {
                return $driverType->is_taxi ?? false;
            }
        }


        return false;
    }

    public function getCommissionAttribute($value)
    {
        return (float) (($value != null && $value != "") ? $value : setting('driversCommission', "0"));
    }


    //
    public function driver_type()
    {
        return $this->hasOne('App\Models\DriverType', 'driver_id', 'id');
    }

    //
    public function currently_assigned_orders()
    {
        return $this->hasMany('App\Models\Order', 'driver_id', 'id')->otherCurrentStatus(['failed', 'cancelled', 'delivered']);
    }
}
