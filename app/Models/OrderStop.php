<?php

namespace App\Models;


use Spatie\MediaLibrary\MediaCollections\Models\Media;

class OrderStop extends BaseModel
{

    protected $appends = ['proof', 'attachments'];

    public function delivery_address()
    {
        return $this->belongsTo('App\Models\DeliveryAddress', 'stop_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }

    public function getProofAttribute()
    {
        return $this->getFirstMediaUrl('proof');
    }

    //MEDIA
    public function getAttachmentsAttribute()
    {
        $mediaFiles = Media::where("model_id", $this->id)->where("model_type", "App\Models\OrderStop")->get();
        $links = $mediaFiles->map(function ($media, $key) {
            return [
                "link" => $media->getFullUrl(),
                "collection_name" => $media->collection_name,
            ];
        });
        return $links;
    }
}
