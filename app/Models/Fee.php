<?php

namespace App\Models;


use App\Traits\HasTranslations;

class Fee extends NoDeleteBaseModel
{

    use HasTranslations;
    public $translatable = ['name'];

     protected $casts = [
        'id' => 'integer',
        'percentage' => 'int',
        'is_active' => 'int',
        'for_admin' => 'int',
    ];
    
}
