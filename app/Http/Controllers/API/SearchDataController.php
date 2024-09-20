<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Service;
use App\Models\Tag;
use App\Models\VendorType;

class SearchDataController extends Controller
{
    //
    public function index(Request $request)
    {

        $tags = Tag::when($request->vendor_type_id, function ($query) use ($request) {
            return $query->where('vendor_type_id', $request->vendor_type_id);
        })->select('id', 'name')->get();
        //
        $vendorType = VendorType::find($request->vendor_type_id);
        $priceRange = [0, 1000];
        if (!empty($vendorType)) {

            if ($vendorType->is_service) {
                $minprice = Service::min('price');
                $maxprice =  Service::max('price');
            } else {
                $minprice = Product::with(["vendor" => function ($query) use ($vendorType) {
                    return $query->where("vendor_Type_id", $vendorType->id);
                }])->min('price');
                $maxprice =   Product::with(["vendor" => function ($query) use ($vendorType) {
                    return $query->where("vendor_Type_id", $vendorType->id);
                }])->max('price');
            }

            $priceRange = [$minprice, $maxprice];
        }


        return response()->json([
            "price_range" => $priceRange,
            "tags" => $tags,
            "vendorType" => $vendorType,
        ], 200);
    }
}