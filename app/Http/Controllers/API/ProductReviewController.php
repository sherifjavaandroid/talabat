<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductReviewResource;
use App\Models\ProductReview;
use Illuminate\Http\Request;


class ProductReviewController extends Controller
{

    //
    public function store(Request $request)
    {

        try {

            $userId = auth()->user()->id ?? null;
            //check for previous review
            $productReview = ProductReview::where([
                "user_id" => $userId,
                "order_id" => $request->order_id,
                "product_id" => $request->product_id,
            ])->first();

            if ($productReview) {
                return response()->json([
                    "message" => __("Product already reviewed"),
                ], 400);
            }

            $request->merge(['user_id' => $userId]);
            $review  = ProductReview::create($request->all());
            return response()->json([
                "message" => __("Product review successful"),
                "review" => $review
            ], 200);
        } catch (\Expection $error) {
            return response()->json([
                "message" => $error->getMessage() ?? __("Failed"),
            ], 400);
        }
    }

    public function index(Request $request)
    {
        return ProductReviewResource::collection(
            ProductReview::with('user')
                ->where('product_id', $request->product_id)
                ->paginate($this->perPage)
        );
    }
}
