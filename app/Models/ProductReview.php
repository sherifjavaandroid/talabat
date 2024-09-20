<?php

namespace App\Models;


class ProductReview extends NoDeleteBaseModel
{
    protected $fillable = [
        "product_id",
        "order_id",
        "user_id",
        "rating",
        "review",
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
