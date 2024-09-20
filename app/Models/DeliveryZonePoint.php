<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZonePoint extends Model
{
    use HasFactory;

    public function delivery_zone()
    {
        return $this->belongsTo('App\Models\DeliveryZone');
    }
}
