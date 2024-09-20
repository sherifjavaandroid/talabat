<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Vendor;

class SharePreviewLivewire extends BaseLivewireComponent
{

    public $type;
    public $model;

    //
    public function mount($type, $mId)
    {
        $this->type = $type;
        //
        if (in_array($type, ["vendor", "vendors"])) {
            $this->model = Vendor::find($mId);
        } else {
            $this->model = Product::find($mId);
        }
    }

    public function render()
    {
        return view('livewire.share_preview')->layout('layouts.blank');
    }
}