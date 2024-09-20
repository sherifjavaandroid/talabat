<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Kirschbaum\PowerJoins\PowerJoins;

class Menu extends BaseModel
{

    use HasTranslations;
    use PowerJoins;

    public $translatable = ['name'];

    protected $fillable = [
        "id", "name", "vendor_id", "is_active"
    ];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }
}
