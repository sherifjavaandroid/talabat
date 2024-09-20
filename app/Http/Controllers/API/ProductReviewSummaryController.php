<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductReviewSummaryController extends Controller
{

    public function index(Request $request)
    {

        $product = Product::find($request->id);
        $product->unsetRelation('vendor');
        $produuct['rating_summary'] = $product->rating_summary;

        return [
            "rating_summary" => $product->rating_summary,
            "latest_reviews" => ProductReview::with('user')->where("product_id",$product->id)->latest()->limit(rand(3,4))->get(),
        ];

    }

}
