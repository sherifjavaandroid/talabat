<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlainVendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //
        return [
            'id' => $this->id,
            'name' => $this->name,
            //nulls
            'email' => "",
            'phone' => "",
            'vendor_type_id' => $this->vendor_type_id,
            'vendor_type' => $this->vendor_type,
            //description
            'description' => $this->description,
            'tax' => $this->tax,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'pickup' => $this->pickup,
            'delivery' => $this->delivery,
            'is_open' => $this->is_open,
            'allow_schedule_order' => $this->allow_schedule_order,
            'min_order' => $this->min_order,
            'max_order' => $this->max_order,
            'prepare_time' => $this->prepare_time,
            'delivery_time' => $this->delivery_time,
            'slots' => $this->slots,
            //
            'logo' => $this->logo,
            'feature_image' => $this->feature_image,
            'rating' => $this->rating,
            'is_package_vendor' => $this->is_package_vendor,
            'days' => $this->days,
            //
            'reviews_count' => $this->reviews_count,

            //dates
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'formatted_date' => $this->formatted_date,

            //misc.
            'can_rate' => $this->can_rate,

        ];
    }
}
