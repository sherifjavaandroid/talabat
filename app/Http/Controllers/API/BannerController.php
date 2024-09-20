<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;


class BannerController extends Controller
{

    //
    public function index(Request $request)
    {
        return BannerResource::collection(
            Banner::inorder()
                /*
                ->when(
                    $request->vendor_type_id,
                    function ($query) use ($request) {
                        return $query->whereHas('category', function ($query) use ($request) {
                            return $query->active()->where('vendor_type_id', $request->vendor_type_id);
                        })->orWhereHas('vendor', function ($query) use ($request) {
                            return $query->active()->where('vendor_type_id', $request->vendor_type_id);
                        });
                    }
                )
                */
                ->when(
                    $request->vendor_type_id,
                    function ($query) use ($request) {
                        return $query->where('vendor_type_id', $request->vendor_type_id);
                    },
                    function ($query) {
                        return $query->whereNull('vendor_type_id')->orWhere('vendor_type_id', '');
                    }
                )
                ->when(
                    $request->featured,
                    function ($query) {
                        return $query->where('featured', '1');
                    }
                )
                ->active()
                ->get()
        );
    }
}