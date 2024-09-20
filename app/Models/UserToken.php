<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'device_uuid',
        'is_mobile',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
