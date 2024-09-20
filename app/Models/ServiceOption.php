<?php

namespace App\Models;


class ServiceOption extends BaseModel
{
    //relationships
    public function option_group()
    {
        return $this->belongsTo('App\Models\ServiceOptionGroup', 'service_option_group_id', 'id');
    }

    public function services()
    {
        return $this->belongsToMany('App\Models\Service', 'option_service');
    }
}
