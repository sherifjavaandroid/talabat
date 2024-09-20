<?php

namespace App\Traits;


use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait OrderAttributeTrait
{


    //TAX
    public function getCodeOrderTypeAttribute()
    {
        if ($this->vendor_id != null) {
            return \Str::upper(substr($this->vendor->vendor_type->slug, 0, 1));
        }
        if ($this->taxi_order) {
            return "T";
        }
        if ($this->package_type) {
            return "P";
        }

        return  "A";
    }

    public function getFormattedCodeAttribute()
    {
        if ($this->vendor_id) {
            return \Str::upper(substr($this->vendor->vendor_type->slug, 0, 1)) . " - " . $this->code;
        }
        if ($this->taxi_order) {
            return "T - " . $this->code;
        }
        if ($this->package_type) {
            return "P - " . $this->code;
        }

        return  $this->code;
    }

    public function getCanAutoAssignDriverAttribute()
    {
        $canAssignOrder = true;
        if ($this->payment_status == "pending" && !in_array($this->payment_method->slug, ['cash', 'wallet'])) {
            $canAssignOrder = false;
        }
        return $canAssignOrder;
    }



    //MEDIA
    public function getAttachmentsAttribute()
    {
        $mediaFiles = Media::where("model_id", $this->id)->where("model_type", "App\Models\Order")->get();
        $links = $mediaFiles->map(function ($media, $key) {
            return [
                "link" => $media->getFullUrl(),
                "collection_name" => $media->collection_name,
            ];
        });
        return $links;
    }


    public function getPayableTotalAttribute()
    {
        $collectDeliveryCash = (bool) setting('finance.delivery.collectDeliveryCash', 0);
        $amount = $collectDeliveryCash ?  ($this->total - $this->delivery_fee) : $this->total;
        return $amount;
    }


    public function getHasOverdraftAttribute()
    {
        return !empty($this->outstanding_balance);
    }
}
