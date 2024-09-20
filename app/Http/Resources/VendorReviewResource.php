<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'review' => $this->review,
            'user_id' => $this->user_id,
            'vendor_id' => $this->vendor_id,
            'driver_id' => $this->driver_id,
            'order_id' => $this->order_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'formatted_date' => $this->formatted_date,
            'formatted_updated_date' => $this->formatted_updated_date,
            'photo' => $this->photo,
            'user' => new PlainUserResource($this->user),
            'vendor' => new PlainVendorResource($this->vendor),
        ];
    }
}
