<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EarningReportResource extends JsonResource
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
            'date' => $this->created_at->format('Y-m-d'),
            'total_earning' => $this->total_earning,
            'total_commission' => $this->total_commission,
            'total_balance' => $this->total_balance,
        ];
    }
}
