<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\ProductAttributeTrait;
use App\Traits\ModelTokenizedAttributeTrait;
use Illuminate\Support\Facades\Auth;
use Kirschbaum\PowerJoins\PowerJoins;


class Product extends BaseModel
{
    use PowerJoins;
    use ProductAttributeTrait;
    use HasTranslations;
    use ModelTokenizedAttributeTrait;
    public $translatable = ['name', "description"];

    protected $fillable = [
        "id", "name", "description", "price", "discount_price",
        "capacity", "unit", "package_count", "available_qty",
        "featured", "deliverable", "is_active", "vendor_id"
    ];

    protected $appends = ['formatted_date', 'sell_price', 'photo', 'is_favourite', 'rating', 'option_groups', 'photos', 'digital_files', 'token'];
    protected $with = ['vendor', 'tags'];
    protected $withCount = ['reviews'];
    protected $casts = [
        'age_restricted' => "bool",
    ];

    public function scopeActive($query)
    {
        $query->where('is_active', 1)->whereHas('vendor', function ($q) {
            $q->where('is_active', 1);
        });

        //check if products table has approved column
        if (\Schema::hasColumn('products', 'approved')) {
            $query->where('approved', 1);
        }

        //
        return $query;
    }

    public function scopeMine($query)
    {

        return $query->when(Auth::user()->hasRole('manager'), function ($query) {
            return $query->where('vendor_id', Auth::user()->vendor_id);
        })->when(Auth::user()->hasRole('city-admin'), function ($query) {
            return $query->whereHas("vendor", function ($query) {
                return $query->where('creator_id', Auth::id());
            });
        });
    }


    // RELATIONSHIPS
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id')->withTrashed();
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    public function sub_categories()
    {
        return $this->belongsToMany('App\Models\Subcategory');
    }

    public function menus()
    {
        return $this->belongsToMany('App\Models\Menu');
    }

    public function options()
    {
        return $this->belongsToMany('App\Models\Option');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }

    public function option_groups()
    {
        // return $this->hasManyThrough(
        //     OptionGroup::class,
        //     ProductOption::class,
        //     'product_id', // Foreign key on the Option table...
        //     'id', // Foreign key on the OptionGroup table...
        //     'id', // Local key on the product table...
        //     'option_group_id' // Local key on the Option table...
        // )->groupBy('id');
    }




    public function sales()
    {
        return $this->hasMany('App\Models\OrderProduct', 'product_id', 'id');
    }

    public function purchases()
    {
        return $this->hasMany('App\Models\OrderProduct')->whereHas(
            "order",
            function ($query) {
                return $query->where("user_id",  auth('sanctum')->user()->id);
            }
        );
    }

    public function favourite()
    {
        return $this->belongsTo('App\Models\Favourite', 'id', 'product_id')->where("user_id", "=", auth('sanctum')->user()->id ?? 0);
    }


    //attribute getters
    public function getOptionGroupsAttribute()
    {

        $optionGroupIds = Option::whereHas('products', function ($query) {
            return $query->where('product_id', "=", $this->id);
        })->active()->pluck('option_group_id');
        //
        $models = OptionGroup::with([
            'options' => function ($query) {
                return $query->whereHas('products', function ($query) {
                    return $query->where('product_id', "=", $this->id);
                });
            }
        ])
            ->whereIn('id', $optionGroupIds)
            ->active()
            ->get();
        return $models;
    }

    public function getHasOptionsAttribute()
    {
        return $this->options()->count() > 0;
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\ProductReview', 'product_id', 'id');
    }

    public function getRatingAttribute()
    {
        return  (float) $this->reviews()->avg('rating');
    }




    public function getIsFavouriteAttribute()
    {

        if (auth('sanctum')->user()) {
            return $this->favourite ? true : false;
        } else {
            return false;
        }
    }



    public function getSellPriceAttribute()
    {
        return ($this->discount_price != null && $this->discount_price > 0) ? $this->discount_price : $this->price;
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

    public function hasTag($tagId)
    {
        return $this->tags()->where('tag_id', $tagId)->exists();
    }

    //
    public function getRatingSummaryAttribute()
    {
        if (!\Schema::hasTable('product_reviews')) {
            return;
        }
        $rates = [5, 4, 3, 2, 1];
        $summary = [];
        $totalReviews = ProductReview::where('product_id', $this->id)->count();
        //
        foreach ($rates as $rate) {
            $rateCount = ProductReview::where('product_id', $this->id)->where("rating", $rate)->count();
            $summary[] = [
                "count" => $rateCount,
                "percentage" => $totalReviews <= 0 ? 0 : ((($rateCount / $totalReviews) * 100) ?? 0),
                "rate" => $rate,
            ];
        }
        //
        return $summary;
    }
}
