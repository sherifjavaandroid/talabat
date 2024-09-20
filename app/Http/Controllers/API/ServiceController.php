<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;


class ServiceController extends Controller
{

    //
    public function index(Request $request)
    {

        return Service::active()->when($request->type == "best", function ($query) {
            return $query->withCount('sales')->orderBy('sales_count', 'DESC');
        })
            ->when($request->keyword, function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->keyword . '%')
                        ->orWhere('description', 'like', '%' . $request->keyword . '%');
                });
            })
            ->when($request->category_id, function ($query) use ($request) {
                return $query->where('category_id', "=", $request->category_id);
            })
            ->when($request->is_open, function ($query) use ($request) {
                return $query->where('is_open', "=", $request->is_open);
            })
            ->when($request->vendor_type_id, function ($query) use ($request) {
                return $query->whereHas('vendor', function ($query) use ($request) {
                    return $query->active()->where('vendor_type_id', $request->vendor_type_id);
                });
            })
            ->when($request->vendor_id, function ($query) use ($request) {
                return $query->active()->where('vendor_id', $request->vendor_id);
            })
            // ->when($request->latitude, function ($query) use ($request) {
            //     return $query->whereHas('vendor', function ($query) use ($request) {
            //         return $query->active()->around($request->latitude, $request->longitude);
            //     });
            // })
            ->when($request->latitude, function ($query) use ($request) {
                return $query->whereHas('vendor', function ($query) use ($request) {
                    return $query->byDeliveryZone($request->latitude, $request->longitude);
                });
            })
            //order by in_order
            ->orderBy('in_order', 'ASC')
            ->when($request->page, function ($query) {
                return $query->paginate($this->perPage);
            }, function ($query) {
                return $query->get();
            });
    }

    public function show(Request $request, $id)
    {
        return Service::find($id);
    }


    public function durations(Request $request)
    {
        return Service::getPossibleEnumValues('duration');
    }
}
