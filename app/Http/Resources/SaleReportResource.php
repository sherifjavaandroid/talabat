<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleReportResource extends JsonResource
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
            'id' => $this->package_type->id ?? $this->serivce->id ?? $this->product->id,
            'name' => $this->package_type->name ?? $this->serivce->name ?? $this->product->name,
            'total_amount' => currencyValueFormat($this->total_amount),
            'total_unit' => (int) $this->total_unit,
            'date' => $this->created_at->format('Y-m-d'),
        ];
    }
}