<?php

namespace App\Models;

class ServiceOptionGroup extends BaseModel
{

    //relationships
    public function options()
    {
        return $this->hasMany('App\Models\ServiceOption', 'service_option_group_id', 'id');
    }
}
