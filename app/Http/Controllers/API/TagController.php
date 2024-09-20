<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;


class TagController extends Controller
{

    //
    public function index(Request $request)
    {
        return Tag::when($request->vendor_type_id, function ($query) use ($request) {
            return $query->where('vendor_type_id', $request->vendor_type_id);
        })->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        })
            ->when($request->page, function ($query) {
                return $query->paginate();
            }, function ($query) {
                return $query->get();
            });
    }
}
