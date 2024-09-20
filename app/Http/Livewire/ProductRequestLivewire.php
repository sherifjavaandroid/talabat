<?php

namespace App\Http\Livewire;

use App\Models\Product;

class ProductRequestLivewire extends BaseLivewireComponent
{

    //
    public $model = Product::class;

    public function render()
    {
        return view('livewire.product_requests');
    }
}
