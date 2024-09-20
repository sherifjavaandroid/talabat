<?php

namespace App\Models;

class Option extends BaseModel
{

    protected $fillable = [
        'name',
        'description',
        'price',
        'product_id',
        'option_group_id',
        'vendor_id',
        'is_active',
        'in_order',
    ];


    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }

    public function option_group()
    {
        return $this->belongsTo('App\Models\OptionGroup', 'option_group_id', 'id');
    }
}
