<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorOpenStatus extends Model
{
    use HasFactory;

    public $fillable = [
        'vendor_id',
        'is_open',
        'auto_set'
    ];
}