<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id ?? null,
            'vendor_type' => $this->vendor_type ?? $this->category->vendor_type,
            'name' => $this->name,
            'photo' => $this->photo,
            'color' => $this->color ?? "#eeeeee",
            'has_subcategories' => (bool) ($this->has_subcategories ?? false),
        ];
    }
}
