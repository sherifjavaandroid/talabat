<?php

namespace App\Models;

class Banner extends BaseModel
{

    protected $appends = ['formatted_date', 'photo'];
    protected $with = ["category", "vendor", "product", 'vendor_type'];

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }
    public function vendor()
    {
        return $this->hasOne('App\Models\Vendor', 'id', 'vendor_id');
    }
    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function vendor_type()
    {
        return $this->hasOne('App\Models\VendorType', 'id', 'vendor_type_id');
    }
}