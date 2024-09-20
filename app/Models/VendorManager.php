<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorManager extends Model
{
    public $timestamps = false;
    protected $fillable = ['vendor_id', 'user_id'];
}
