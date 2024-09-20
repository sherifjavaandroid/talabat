<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentPage extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'content',
        'is_active',
    ];

    //scope for active
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
