<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodVehicleType extends NoDeleteBaseModel
{
    use HasFactory;
    public $table = "payment_method_vehicle_type";
    public $timestamps = false;

    protected $with = [
        'vehicle_type',
        'payment_method'
    ];

    public function vehicle_type()
    {
        return $this->belongsTo(VehicleType::class);
    }
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

}
