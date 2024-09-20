<?php

namespace App\Models;


class LoyaltyPointReport extends BaseModel
{
    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id')->withTrashed();
    }

    public function loyalty_point()
    {
        return $this->belongsTo('App\Models\LoyaltyPoint', 'loyalty_point_id', 'id');
    }
}
