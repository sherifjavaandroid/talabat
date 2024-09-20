<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kirschbaum\PowerJoins\PowerJoins;
use Illuminate\Database\Eloquent\Model;
use Malhal\Geographical\Geographical;

class TaxiZone extends Model
{
    use HasFactory;
    use PowerJoins;
    use Geographical;
    protected static $kilometers = true;


    public function points()
    {
        return $this->hasMany('App\Models\TaxiZonePoint');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', '=', 1);
    }

    public function scopeCloseTo($query, $latitude, $longitude)
    {
        return $query
        ->selectRaw('radius AS deliveryZoneRange')
        ->distance($latitude, $longitude)
            ->havingRaw("deliveryZoneRange >= distance")->orderBy('distance', 'ASC');
    }
}
