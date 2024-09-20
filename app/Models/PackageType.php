<?php

namespace App\Models;

use App\Traits\HasTranslations;

class PackageType extends BaseModel
{
    use HasTranslations;
    public $translatable = ['name',"description"];

    public function package_type_pricings(){
        return $this->hasMany(PackageTypePricing::class);
    }
}
