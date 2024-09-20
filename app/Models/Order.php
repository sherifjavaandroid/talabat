<?php

namespace App\Models;

use App\Traits\CustomHasStatuses;
use App\Traits\FirebaseMessagingTrait;
use App\Traits\FirebaseDBTrait;
use App\Traits\OrderAttributeTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Kirschbaum\PowerJoins\PowerJoins;

class Order extends BaseModel
{
    use PowerJoins;

    use FirebaseMessagingTrait, FirebaseDBTrait;
    use CustomHasStatuses, OrderAttributeTrait;


    // protected $fillable = ["note", "reason", "sub_total", "total", "driver_id", "delivery_fee", "payment_method_id"];
    //remove some data to prevent mass assignment by user
    protected $fillable = ["note", "reason", "payment_method_id"];
    protected $with = ["user", "driver", 'statuses', 'stops', 'order_service', 'taxi_order'];
    protected $appends = [
        "payment_link", 'formatted_date', 'type', 'formatted_type',
        'can_rate', 'can_rate_driver', 'can_cancel',
        'status',
        'pickup_location', 'dropoff_location',
        'photo', 'signature', 'attachments'
    ];

    protected $casts = [
        'total' => 'double',
    ];


    public function scopeFullData($query, $extraWiths = [])
    {

        $fixedWiths = [
            "products.product", "stops.delivery_address", "user", "driver.vehicle", "delivery_address", "payment_method", "vendor" => function ($query) {
                return $query->withTrashed();
            }, 'package_type'
        ];

        $withs = array_merge($fixedWiths, $extraWiths);
        return $query->with($withs);
    }

    public function scopeMine($query)
    {
        return $query->when(Auth::user()->hasRole('manager'), function ($query) {
            return $query->where('vendor_id', Auth::user()->vendor_id);
        })->when(Auth::user()->hasRole('city-admin'), function ($query) {
            return $query->whereHas('vendor', function ($query) {
                return $query->where('creator_id', Auth::id());
            });
        });
    }

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }

    public function stops()
    {
        return $this->hasMany('App\Models\OrderStop', 'order_id', 'id')->withTrashed();
    }

    public function order_service()
    {
        return $this->belongsTo('App\Models\OrderService', 'id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\User', 'driver_id', 'id')->withTrashed();
    }

    public function delivery_address()
    {
        return $this->belongsTo('App\Models\DeliveryAddress', 'delivery_address_id', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo('App\Models\PaymentMethod', 'payment_method_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id')->withTrashed();
    }

    public function payment()
    {
        return $this->belongsTo('App\Models\Payment', 'id', 'order_id');
    }

    public function taxi_order()
    {
        return $this->hasOne('App\Models\TaxiOrder', 'order_id', 'id');
    }

    public function outstanding_balance()
    {
        return $this->hasOne('App\Models\OutstandingBalance', 'order_id', 'id')->where('completed', 0);
    }


    //
    public function package_type()
    {
        return $this->belongsTo('App\Models\PackageType', 'package_type_id', 'id');
    }

    public function auto_assignment()
    {
        return $this->hasOne('App\Models\AutoAssignment', 'order_id', 'id')->where('status', "pending");
    }

    //review relationship
    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'order_id', 'id');
    }


    public function getPickupLocationAttribute()
    {
        if (count($this->stops) > 0) {
            return $this->stops->first()->delivery_address;
        } else {
            return null;
        }
    }

    public function getDropoffLocationAttribute()
    {
        if (count($this->stops) > 1) {
            return $this->stops->last()->delivery_address;
        } else {
            return null;
        }
    }

    public function getTypeAttribute()
    {
        return $this->vendor->vendor_type->slug ?? '';
    }




    //
    public function getCanRateAttribute()
    {

        if (empty(Auth::user())) {
            return false;
        }
        //
        $vendorReview = Review::where('user_id', Auth::id())->where('order_id', $this->id)->first();
        return empty($vendorReview);
    }

    public function getCanRateDriverAttribute()
    {

        if (empty(Auth::user())) {
            return false;
        }
        //
        $driverReview = Review::where('user_id', Auth::id())->where('driver_id', $this->driver_id)->first();
        return empty($driverReview);
    }

    public function getCanCancelAttribute()
    {
        $preventOrderCancellation = setting('finance.preventOrderCancellation', "");
        if (!is_array($preventOrderCancellation)) {
            $preventOrderCancellation = json_decode(setting('finance.preventOrderCancellation', ""), true) ?? [];
        }
        return !in_array($this->status, $preventOrderCancellation);
    }

    public function getPaymentLinkAttribute()
    {

        if ($this->payment_status == "pending") {
            return route('order.payment', ["code" => $this->code]);
        } else {
            return "";
        }
    }

    //TODO
    public function getFormattedTypeAttribute()
    {
        return Str::ucfirst($this->vendor->vendor_type->name ?? '');
    }
    public function getIsPackageAttribute()
    {
        return ($this->vendor->vendor_type->slug ?? '') == "package";
    }

    public function getIsServiceAttribute()
    {
        return ($this->vendor->vendor_type->slug ?? '') == "service";
    }

    public function getIsBookingAttribute()
    {
        return ($this->vendor->vendor_type->slug ?? '') == "booking";
    }

    public function getIsFoodAttribute()
    {
        return in_array(($this->vendor->vendor_type->slug ?? ''), ["food", "grocery", "pharmacy"]);
    }

    public function getCanEditProductsAttribute()
    {
        return !in_array(($this->vendor->vendor_type->slug ?? ''), ["taxi", "parcel", "service", "booking"]);
    }

    public function getOrderTypeAttribute()
    {
        if (empty($this->vendor)) {
            return "taxi";
        }
        //
        return $this->vendor->vendor_type->slug;
    }

    public function getCurrencyCode()
    {
        return ($this->taxi_order != null && $this->taxi_order->currency != null) ? $this->taxi_order->currency->code : setting('currencyCode', 'USD');
    }

    public function getCurrencySymbolAttribute()
    {
        return ($this->taxi_order != null && $this->taxi_order->currency != null) ? $this->taxi_order->currency->symbol : setting('currency', '$');
    }

    //get payment status color
    public function getPaymentStatusColorAttribute()
    {
        $status = $this->payment_status ?? "pending";
        return setting("appColorTheme.$status" . "Color", '#0099FF');
    }


    public function getSignatureAttribute()
    {
        return $this->getFirstMediaUrl('signature');
    }

    public function getStatusAttribute()
    {
        return $this->status() != null ?  $this->status()->name : "pending";
    }

    public function getDeliveryPhotoAttribute()
    {
        return $this->getFirstMediaUrl('delivery_photo');
    }

    public static function getPossibleStatues()
    {
        return ['pending', 'preparing', 'ready', 'enroute', 'delivered', 'failed', 'cancelled'];
    }

    //updating wallet balance is order failed and was paid via wallet
    public function refundUser($force = false)
    {
        //'pending','preparing','ready','enroute','delivered','failed','cancelled'
        if (
            in_array($this->status, ['failed', 'cancelled']) &&
            in_array($this->payment_status, ['successful'])  &&
            $this->payment_method != null &&
            $this->payment_method->slug != "cash"
        ) {
            //fetch refund by order id
            $refund = Refund::where('order_id', $this->id)->first();
            //if there is a refund
            if (!empty($refund) && $refund->status == "successful") {
                return;
            }

            //create refund
            if ($refund == null) {
                $refund = new Refund();
                $refund->order_id = $this->id;
            }

            //if there is no refund
            if ($force || (bool) setting('finance.autoRefund', true)) {
                //update user wallet
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $this->user_id],
                    ['balance' => 0]
                );

                //
                if ((bool) setting('finance.delivery.collectDeliveryCash', 0)) {
                    $wallet->balance += ($this->total - $this->delivery_fee);
                } else {
                    $wallet->balance += $this->total;
                }
                $wallet->save();

                //save wallet transactions
                $walletTransaction = new WalletTransaction();
                $walletTransaction->wallet_id = $wallet->id;
                $walletTransaction->amount = $this->total;
                $walletTransaction->reason = "Refund";
                $walletTransaction->status = "successful";
                $walletTransaction->is_credit = 1;
                $walletTransaction->save();

                //sent the refund to success
                $refund->status = "successful";
            }
            //save refund
            $refund->save();
        }
    }

    public function refundVendorProducts($force = false)
    {
        //'pending','preparing','ready','enroute','delivered','failed','cancelled'
        if (
            in_array($this->status, ['failed', 'cancelled'])
        ) {

            foreach ($this->products as $orderProduct) {
                $product = $orderProduct->product;
                if ($product->available_qty != null) {
                    $product->available_qty += $orderProduct->quantity;
                    $product->saveQuietly();
                }
            }
        }
    }
}