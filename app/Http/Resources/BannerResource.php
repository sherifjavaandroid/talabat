<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'category' => new CategoryResource($this->category),
            'photo' => $this->photo,
            'vendor' => $this->vendor,
            'product' => $this->product,
            'vendor_type' => $this->vendor_type,
            'link' => $this->link,
        ];
    }
}