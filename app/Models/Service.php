<?php

namespace App\Models;

use Kirschbaum\PowerJoins\PowerJoins;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasTranslations;
use App\Traits\ModelTokenizedAttributeTrait;

class Service extends NoDeleteBaseModel
{
    use PowerJoins;
    use HasTranslations;
    use ModelTokenizedAttributeTrait;
    public $translatable = ['name', "description"];

    protected $fillable = [
        "name", "description", "vendor_id", "category_id", "subcategory_id", "price", "discount_price", "duration", "is_active", 'location',
    ];
    protected $appends = ['formatted_date', 'photo', 'photos', 'option_groups', 'token'];
    protected $with = ['vendor', 'category', 'subcategory'];

    protected $casts = [
        'location' => "bool",
        'age_restricted' => "bool",
    ];

    public function scopeMine($query)
    {

        return $query->when(Auth::user()->hasRole('manager'), function ($query) {
            return $query->where('vendor_id', Auth::user()->vendor_id);
        })->when(Auth::user()->hasRole('city-admin'), function ($query) {
            return $query->where('creator_id', Auth::id());
        });
    }


    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id')->withTrashed();
    }


    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Models\Subcategory', 'subcategory_id', 'id');
    }

    public function sales()
    {
        return $this->hasMany('App\Models\OrderService', 'service_id', 'id');
    }

    public function getPhotosAttribute()
    {
        $mediaItems = $this->getMedia('default');
        $photos = [];

        foreach ($mediaItems as $mediaItem) {
            array_push($photos, $mediaItem->getFullUrl());
        }
        return $photos;
    }

    public function getPerHourAttribute()
    {
        return $this->duration == "hour";
    }

    public function getSellPriceAttribute()
    {
        return $this->discount_price ? $this->discount_price : $this->price;
    }

    public function getOptionGroupsAttribute()
    {

        $optionGroupIds = ServiceOption::whereHas('services', function ($query) {
            return $query->where('service_id', "=", $this->id);
        })->active()->pluck('service_option_group_id');

        //
        return ServiceOptionGroup::with(['options' => function ($query) {
            $query->whereHas('services', function ($query) {
                return $query->where('service_id', "=", $this->id);
            });
        }])->whereIn('id', $optionGroupIds)->active()->get();
    }

    public function options()
    {
        return $this->hasMany('App\Models\ServiceOption');
    }
}
