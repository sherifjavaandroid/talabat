<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlainUserResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'email' => '',
            'phone' => '',
            'role_name' => $this->role_name,
            'photo' => $this->photo,
            'rating' => $this->rating,
            'assigned_orders' => $this->assigned_orders,
            'formatted_date' => $this->formatted_date,
        ];
    }
}
