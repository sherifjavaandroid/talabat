<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;


class CategoryController extends Controller
{

    //
    public function index(Request $request)
    {

        $perPage = setting("ui.categorySize.page", 8);
        //
        if(!empty($request->full) && (bool) $request->full){
            $perPage = setting("ui.categorySize.page", 8) * 3;
        }

        // vendor_type_id
        if ($request->type == "sub") {
            return CategoryResource::collection(
                Subcategory::active()->inorder()
                ->when($request->category_id, function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                })
                ->when($request->vendor_type_id, function ($query) use ($request) {
                    return $query->whereHas('category', function ($query) use ($request) {
                        return $query->where('vendor_type_id', $request->vendor_type_id);
                    });
                })
                    ->when($request->page, function ($query) use ($perPage) {
                        return $query->paginate($perPage);
                    }, function ($query) {
                        return $query->get();
                    })
            );
        }
        return CategoryResource::collection(
            Category::active()->inorder()->when($request->vendor_type_id, function ($query) use ($request) {
                return $query->where('vendor_type_id', $request->vendor_type_id);
            })
                ->when($request->page, function ($query) use ($perPage) {
                    return $query->paginate($perPage);
                }, function ($query) {
                    return $query->get();
                })
        );
    }
}
