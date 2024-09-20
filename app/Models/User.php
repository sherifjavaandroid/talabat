<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use willvincent\Rateable\Rateable;
use Kirschbaum\PowerJoins\PowerJoins;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Traits\EloquentRelationshipTrait;
use App\Traits\DriverAttributeTrait;
use App\Traits\DocumentRequestTrait;
use App\Traits\UserFirebaseTokenAttributeTrait;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia, HasRoles, HasApiTokens, Rateable, SoftDeletes;
    use PowerJoins;
    use EloquentRelationshipTrait;
    use DriverAttributeTrait;
    use DocumentRequestTrait;
    use UserFirebaseTokenAttributeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'vendor_id' => 'int',
    ];

    protected $appends = [
        'role_name',
        'role_id',
        'formatted_date',
        'photo',
        'rating',
        'assigned_orders',
        'raw_phone',
        'is_taxi_driver',
        'document_requested',
        'pending_document_approval',
    ];



    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('profile')
            ->useFallbackUrl('' . url('') . '/images/user.png')
            ->useFallbackPath(public_path('/images/user.png'));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function getPhotoAttribute()
    {
        return $this->getFirstMediaUrl('profile');
    }

    public function getRoleNameAttribute()
    {
        return $this->roles->first()->name ?? "";
    }

    public function getRoleIdAttribute()
    {
        return $this->roles->first()->id ?? "";
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at != null ? $this->created_at->translatedFormat('d M Y') : $this->created_at;
    }

    public function getRatingAttribute()
    {
        return  (string) ($this->averageRating ?? 3);
    }



    public function getAssignedOrdersAttribute()
    {
        return  Order::where("driver_id", $this->id)->otherCurrentStatus(['failed', 'cancelled', 'delivered'])->count() ?? 0;
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

    public function getQrcodeLoginAttribute()
    {
        return encrypt([
            "id" => $this->id,
            "email" => $this->email,
            "code" => $this->code,
        ]);
    }

    public function getCountryCodeAttribute($value)
    {
        if (empty($value)) {
            try {
                return (new PhoneNumber($this->phone))->getCountry();
            } catch (\Exception $ex) {
                return "us";
            }
        }
        return $value;
    }

    public function getRawPhoneAttribute()
    {
        try {
            return str_replace(" ", "", (new PhoneNumber($this->phone))->formatNational());
        } catch (\Exception $ex) {
            return $this->phone;
        }
    }


    public function scopeManager($query)
    {
        return $query->role('manager');
    }

    public function scopeAdmin($query)
    {
        return $query->role('admin');
    }

    public function scopeClient($query)
    {
        return $query->role('client');
    }



    //
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }

    //vendors
    public function vendors()
    {
        return $this->hasMany('App\Models\Vendor', 'creator_id', 'id');
    }

    public function vehicle()
    {
        // return $this->hasMany('App\Models\Vehicle', 'driver_id', 'id');
        return $this->hasOne('App\Models\Vehicle', 'driver_id', 'id')->where('is_active', 1)->where('verified', 1);
    }

    // public function getVehicleAttribute()
    // {
    //     return $this->vehicles()->first(function ($value, $key) {
    //         return $value->is_active == 1 && $value->verified == 1;
    //     });
    // }


    public function wallet()
    {
        return $this->hasOne('App\Models\Wallet', 'user_id', 'id');
    }

    public function payment_accounts()
    {
        return $this->morphMany('App\Models\PaymentAccount', 'accountable');
    }


    //NOTIFICATION
    function syncFCMToken($token)
    {

        try {
            if (!empty($token)) {
                $userToken = UserToken::create([
                    "user_id" => \Auth::id(),
                    "token" => $token
                ]);
            }
        } catch (\Exception $ex) {
            \Log::debug([
                "Error" => $ex->getMessage()
            ]);
        }
    }

    //NOTIFICATION
    function clearFCMToken()
    {
        UserToken::where("user_id", \Auth::id())->delete();
    }


    //Wallet
    public function createWallet()
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' =>  $this->id],
            ['balance' => 0.00]
        );
    }

    public function updateWallet($amount, $reason = null)
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' =>  $this->id],
            ['balance' => 0.00]
        );

        //
        $newAmount = $amount - $wallet->balance;
        $wallet->balance = $amount;
        $wallet->save();


        //
        $walletTransaction = new WalletTransaction();
        $walletTransaction->amount = $newAmount >= 0 ? $newAmount : ($newAmount * -1);
        $walletTransaction->wallet_id = $wallet->id;
        $walletTransaction->is_credit = $newAmount >= 0 ? 1 : 0;
        $walletTransaction->reason = $reason != null ? $reason : ($newAmount >= 0 ? "Topup" : "Debit");
        $walletTransaction->ref = "lt_" . \Str::random(10);
        $walletTransaction->status = "successful";
        $walletTransaction->save();
        return $wallet;
    }

    public function topupWallet($amount, $reason = null)
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' =>  $this->id],
            ['balance' => 0.00]
        );

        //
        $wallet->balance += $amount;
        $wallet->save();

        //
        if (!empty($reason)) {
            $walletTransaction = new WalletTransaction();
            $walletTransaction->amount = $amount;
            $walletTransaction->wallet_id = $wallet->id;
            $walletTransaction->is_credit = 1;
            $walletTransaction->reason = $reason;
            $walletTransaction->ref = "lt_" . \Str::random(10);
            $walletTransaction->status = "successful";
            $walletTransaction->save();
        }
        return $wallet;
    }
}
