<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Tag extends Model
{
    use HasFactory;
    
    use HasTranslations;
    public $translatable = ['name'];

    protected $fillable = ['name',"vendor_type_id"];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }

    public function vendor_type()
    {
        return $this->belongsTo('App\Models\VendorType');
    }
}
