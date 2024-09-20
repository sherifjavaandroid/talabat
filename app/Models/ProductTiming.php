<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTiming extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'day_id',
        'start_time',
        'end_time',
    ];

    //day
    public function day()
    {
        return $this->belongsTo(Day::class);
    }


    //product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
