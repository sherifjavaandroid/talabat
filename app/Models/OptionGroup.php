<?php

namespace App\Models;

class OptionGroup extends BaseModel
{

    protected $fillable = [
        'name',
        'vendor_id',
        'multiple',
        'required',
        'is_active',
        'in_order',
        'max_options',
    ];


    public function options()
    {
        return $this->hasMany('App\Models\Option', 'option_group_id', 'id')->active();
    }
}
