<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductMiscController extends Controller
{

    public function frequent(Request $request)
    {

        $boughtTogetherProductIds =  DB::table(DB::raw('order_products as b'))
            ->selectRaw('a.product_id as product_id, b.product_id as bought_with, count(*) as times_bought_together')
            ->join(DB::raw('order_products as a'), function ($join) {
                $join->on('a.order_id', '=', 'b.order_id');
                $join->on('a.product_id', '!=', 'b.product_id');
            })
            ->groupBy('a.product_id', 'b.product_id')->where('b.product_id', $request->id)->pluck('product_id');

        $products = Product::whereNull('available_qty')->whereIn("id", $boughtTogetherProductIds)->get();
        $mProducts = Product::where('available_qty', ">", 0)->whereIn("id", $boughtTogetherProductIds)->get();

        $products = collect($products)->merge($mProducts);

        return response()->json([
            "products" => $products,
        ], 200);
    }
}
