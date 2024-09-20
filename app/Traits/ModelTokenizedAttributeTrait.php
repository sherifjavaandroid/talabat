<?php

namespace App\Traits;


trait ModelTokenizedAttributeTrait
{
    public function getTokenAttribute()
    {
        //encrypt the id, price, name, discount_price
        $payload = [];
        $payload['id'] = $this->id;
        $payload['price'] = $this->price;
        $payload['discount_price'] = $this->discount_price;

        $payload = json_encode($payload);
        $token = \Crypt::encrypt($payload);
        return $token;
    }

    public function verifyToken($token)
    {
        $payload = \Crypt::decrypt($token);
        $payload = json_decode($payload);

        if ($payload->id == $this->id && $payload->price == $this->price && $payload->discount_price == $this->discount_price) {
            return true;
        }
        return false;
    }
}
