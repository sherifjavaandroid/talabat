<?php

namespace App\Models;

use Kirschbaum\PowerJoins\PowerJoins;
use App\Traits\HasTranslations;

class Category extends BaseModel
{
    use PowerJoins;
    use HasTranslations;
    public $translatable = ['name'];
    
    protected $fillable = [
        'id', 'name', 'vendor_type_id', 'is_active'
    ];
    protected $with = ['vendor_type'];

    public function vendor_type()
    {
        return $this->belongsTo('App\Models\VendorType', 'vendor_type_id', 'id');
    }

    public function vendors()
    {
        return $this->belongsToMany('App\Models\Vendor');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }

    public function services()
    {
        return $this->hasOne('App\Models\Service');
    }

    public function sub_categories()
    {
        return $this->hasMany('App\Models\Subcategory');
    }

    public function getHasSubcategoriesAttribute()
    {
        return $this->sub_categories()->exists() ?? false;
    }
}
