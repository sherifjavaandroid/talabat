<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earned extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'vendor_id',
        'order_id',
    ];
}
