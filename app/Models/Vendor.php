<?php

namespace App\Models;

use App\Traits\GoogleMapApiTrait;
use App\Traits\VendorAttributeTrait;
use App\Traits\VendorSlotTrait;
use App\Traits\DocumentRequestTrait;
use Malhal\Geographical\Geographical;
use Illuminate\Support\Facades\Auth;
use willvincent\Rateable\Rateable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Kirschbaum\PowerJoins\PowerJoins;

class Vendor extends BaseModel
{

    use  Geographical, Rateable, PowerJoins, GoogleMapApiTrait;
    use VendorAttributeTrait;
    use VendorSlotTrait;
    use DocumentRequestTrait;

    protected static $kilometers = true;
    protected $casts = [
        'id' => 'integer',
        'allow_schedule_order' => 'boolean',
        'has_sub_categories' => 'boolean',
        'has_subscription' => 'boolean',
        'use_subscription' => 'boolean',
        'vendor_type_id' => 'int',
        'pickup' => 'int',
        'delivery' => 'int',
        'is_active' => 'int',
        'reviews_count' => 'int',
        'is_open' => 'boolean',
    ];
    protected $appends = [
        'formatted_date', 'logo', 'feature_image', 'rating', 'can_rate', 'slots', 'is_package_vendor', 'has_subscription',
        'document_requested', 'pending_document_approval'
    ];
    protected $with = ['vendor_type', 'fees'];
    protected $withCount = ['reviews'];

    protected $fillable = [
        "id", "name", "description", "delivery_fee", "delivery_range", "tax", "phone", "email", "address", "latitude", "longitude", "commission", "pickup", "delivery", "is_active", "charge_per_km", "is_open", "vendor_type_id"
    ];


    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('logo')
            ->useFallbackUrl('' . url('') . '/images/default.png')
            ->useFallbackPath(public_path('/images/default.png'));
        $this
            ->addMediaCollection('feature_image')
            ->useFallbackUrl('' . url('') . '/images/default.png')
            ->useFallbackPath(public_path('/images/default.png'));
    }

    public function scopeIsPackageDelivery($query)
    {
        return $query->where('is_package_vendor', 1);
    }

    public function scopeRegularVendor($query)
    {
        return $query->where('is_package_vendor', 0);
    }

    public function scopeMine($query)
    {

        return $query->when(Auth::user()->hasRole('manager'), function ($query) {
            return $query->where('id', Auth::user()->vendor_id);
        })->when(Auth::user()->hasRole('city-admin'), function ($query) {
            return $query->where('creator_id', Auth::id());
        });
    }

    public function scopeCloseTo($query, $latitude, $longitude)
    {
        return $query->whereRaw("
       ST_Distance_Sphere(
            point(longitude, latitude),
            point(?, ?)
        ) * .000621371192 <= radius
    ", [
            $longitude,
            $latitude,
        ]);
    }

    public function scopeInRange($query, $latitude, $longitude)
    {
        return $query->select("delivery_range", "latitude", "longitude")
            ->distance($latitude, $longitude)
            ->havingRaw("delivery_range >= distance");
    }

    public function scopeWithInRange($query, $latitude, $longitude)
    {
        return $query->select("delivery_range", "latitude", "longitude")
            ->distance($latitude, $longitude)
            ->havingRaw("delivery_range >= distance");
    }


    public function scopeAround($query, $latitude, $longitude)
    {
        return $query->select("delivery_range", "latitude", "longitude")
            ->distance($latitude, $longitude)
            ->havingRaw("delivery_range >= distance")
            ->orWhereHas('delivery_zones', function ($query) use ($latitude, $longitude) {
                $query->closeTo($latitude, $longitude);
            });
    }

    public function scopeWithIn($query, $latitude, $longitude)
    {
        return $query->whereHas('delivery_zones', function ($query) use ($latitude, $longitude) {
            $query->closeTo($latitude, $longitude);
        });
    }




    public function getLogoAttribute()
    {
        return $this->getFirstMediaUrl('logo');
    }
    public function getFeatureImageAttribute()
    {
        return $this->getFirstMediaUrl('feature_image');
    }

    // public function getTypeAttribute()
    // {
    //     return $this->vendor_type()->name ?? "Regular";
    // }

    public function getRatingAttribute()
    {
        return  (int) ($this->averageRating ?? setting("defaultVendorRating", 3));
    }


    public function getIsPackageVendorAttribute($value)
    {

        // is_package_vendor
        if (Schema::hasColumn('vendors', 'is_package_vendor')) {
            return $value;
        } else {
            $type = $this->vendor_type;
            // logger("Vendor", [$this->name, $type]);
            if ($type->slug ?? '' == "parcel") {
                return 1;
            } else {
                return 0;
            }
        }
    }


    // public function getIsOpenAttribute($value)
    // {
    //     $value = $this->getRawOriginal('is_open');
    //     // if ($this->id == 175) {
    //     //     logger("openNow", [$this->openNow]);
    //     // }
    //     if (!$value) {
    //         return false;
    //     } else if (count($this->days) == 0) {
    //         return true;
    //     } else if (count($this->openToday) > 0 && count($this->openNow) > 0) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function getCanRateAttribute()
    {

        if (empty(Auth::user())) {
            return false;
        }
        //
        $vendorReview = Review::where('user_id', Auth::id())->where('vendor_id', $this->id)->first();
        return empty($vendorReview);
    }

    public function getSlotsAttribute()
    {

        $slots = [];
        $days = $this->days->pluck('name')->toArray();
        $daysTiming = $this->days;
        if (!empty($days)) {
            //max order schedule days
            $daysCount = setting('maxScheduledDay', 5) + 1;
            $maxScheduledTime = setting('maxScheduledTime', 2);
            $currentTime = now()->format('H:s:i');

            //
            for ($i = 0; $i < $daysCount; $i++) {
                $date = Carbon::now()->addDays($i);
                $dateDayName = $date->format('l');
                try {
                    if (in_array($dateDayName, $days)) {
                        $schuldeInfo = [];
                        $schuldeInfo["date"] = $date;
                        //times
                        $dayTimingIndex = array_search($dateDayName, $days);
                        $dayTiming = $daysTiming[$dayTimingIndex];

                        //
                        $schuldeInfo = $this->getSlotTimes($schuldeInfo, $i, $dayTiming, $maxScheduledTime, $currentTime);
                        //
                        array_push($slots, $schuldeInfo);
                    }
                } catch (\Exception $ex) {
                    logger("Error", [$ex]);
                }
            }
        }

        return $slots;
    }


    public function getDocumentsAttribute()
    {
        $mediaItems = $this->getMedia('documents');
        $photos = [];

        foreach ($mediaItems as $mediaItem) {
            array_push($photos, $mediaItem->getFullUrl());
        }
        return $photos;
    }



    //RELATIONSHIPS
    public function vendor_type()
    {
        return $this->belongsTo('App\Models\VendorType', 'vendor_type_id', 'id');
    }

    public function earning()
    {
        return $this->hasOne('App\Models\Earning', 'vendor_id', 'id');
    }

    public function managers()
    {
        return $this->hasMany('App\Models\User', 'vendor_id', 'id');
    }

    //START SALES
    public function sales()
    {
        return $this->hasMany('App\Models\Order', 'vendor_id', 'id');
    }
    public function successful_sales()
    {
        return $this->hasMany('App\Models\Order', 'vendor_id', 'id')->currentStatus('successful', 'delivered');
    }
    public function pending_sales()
    {
        return $this->hasMany('App\Models\Order', 'vendor_id', 'id')->currentStatus('pending');
    }
    public function cancelled_sales()
    {
        return $this->hasMany('App\Models\Order', 'vendor_id', 'id')->currentStatus('cancelled');
    }
    //END SALES

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }


    public function delivery_zones()
    {
        return $this->belongsToMany('App\Models\DeliveryZone');
    }

    public function days()
    {
        return $this->belongsToMany('App\Models\Day')->withPivot('id', 'day_id', 'open', 'close');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'vendor_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'vendor_id', 'id');
    }

    public function menus()
    {
        return $this->hasMany('App\Models\Menu')->where('is_active', 1)->orderBy('in_order', 'asc');
    }

    public function cities()
    {
        return $this->belongsToMany('App\Models\City');
    }
    public function states()
    {
        return $this->belongsToMany('App\Models\State');
    }
    public function countries()
    {
        return $this->belongsToMany('App\Models\Country');
    }

    public function payment_methods()
    {
        return $this->belongsToMany('App\Models\PaymentMethod');
    }

    public function payment_accounts()
    {
        return $this->morphMany('App\Models\PaymentAccount', 'accountable');
    }

    public function delivery_zone()
    {
        return $this->belongsTo('App\Models\DeliveryZone');
    }


    public function package_types_pricing()
    {
        return $this->hasMany('App\Models\PackageTypePricing', 'vendor_id', 'id');
    }

    public function openToday()
    {
        $now = Carbon::now();
        $todayName = $now->format('l');
        return $this->belongsToMany('App\Models\Day')->withPivot('open', 'close')->where('name', $todayName);
    }

    public function openNow()
    {
        $now = Carbon::now();
        $nowTime = $now->format('H:i:s');
        $todayName = $now->format('l');
        return $this->belongsToMany('App\Models\Day')->withPivot('open', 'close')->whereTime('open', '<=', $nowTime)->whereTime('close', '>', $nowTime)->where('name', $todayName);
    }

    public function getHasSubscriptionAttribute()
    {

        if ($this->use_subscription) {
            $subscriptionVendor = SubscriptionVendor::where('vendor_id', $this->id)
                ->whereDate('expires_at', '>', Carbon::now())
                ->where('status', 'successful')
                ->first();
            if (empty($subscriptionVendor)) {
                return false;
            } else {
                return true;
            }
        }
        return true;
    }
}
