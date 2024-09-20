<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Service;
use App\Models\Vendor;
use App\Traits\GoogleMapApiTrait;

class SearchController extends Controller
{
    use GoogleMapApiTrait;
    //
    public function index(Request $request)
    {

        $products = [];
        $vendors = [];
        $services = [];
        $searchResult = null;

        //
        $tags = [];
        if ($request->tags) {
            if (!is_array($request->tags)) {
                $tags = json_decode($request->tags, true) ?? [];
            } else {
                $tags = $request->tags;
            }

            //
            if (!is_array($tags)) {
                $tags = [$tags];
            }
        }

        //
        if (empty($request->type) || $request->type == "product") {

            $products = $searchResult = Product::active()
                ->currentlyOpen()
                //tag search
                ->when(!empty($tags), function ($query) use ($tags) {
                    return $query->whereHas('tags', function ($query) use ($tags) {
                        return $query->whereIn('id', $tags);
                    });
                })
                ->when($request->category_id, function ($query) use ($request) {
                    return $query->whereHas("categories", function ($query) use ($request) {
                        return $query->where('category_id', "=", $request->category_id);
                    });
                })
                ->when($request->subcategory_id, function ($query) use ($request) {
                    return $query->whereHas("sub_categories", function ($query) use ($request) {
                        return $query->where('subcategory_id', "=", $request->subcategory_id);
                    });
                })
                ->when($request->vendor_id, function ($query) use ($request) {
                    return $query->where('vendor_id', $request->vendor_id);
                })

                //
                ->when($request->vendor_type_id && empty($request->latitude), function ($query) use ($request) {
                    return $this->filterByVendorTypeId($query, $request);
                })

                ->when($request->filter == "best", function ($query) {
                    return $query->withCount('sales')->orderBy('sales_count', 'DESC');
                })
                ->when($request->filter == "you", function ($query) {

                    if (auth('sanctum')->user()) {
                        return $query->whereHas('purchases')->withCount('purchases')->orderBy('purchases_count', 'DESC');
                    } else {
                        return $query->inRandomOrder();
                    }
                })
                ->when($request->min_price, function ($query) use ($request) {
                    return $query->where('price', ">=", $request->min_price);
                })
                ->when($request->max_price, function ($query) use ($request) {
                    return $query->where('price', "<=", $request->max_price);
                })
                ->when($request->sort, function ($query) use ($request) {
                    return $query->orderBy('name', $request->sort);
                })
                ->when($request->keyword, function ($query) use ($request) {
                    // return $query->where('name', "like", "%" . $request->keyword . "%");
                    //use where raw to search
                    return $query->whereRaw('name LIKE ?', ["%$request->keyword%"]);
                })
                // ->when($request->latitude && empty($request->vendor_type_id), function ($query) use ($request) {
                //     return $query->where(function ($query) use ($request) {
                //         return $this->filterByVendorLocation($query, $request);
                //     });
                // })
                // ->when($request->latitude && $request->vendor_type_id, function ($query) use ($request) {
                //     return $query->where(function ($query) use ($request) {
                //         return $this->filterByVendorChecks($query, $request);
                //     });
                // })

                /*
                ->when($request->vendor_type_id, function ($query) use ($request) {
                    return $this->filterByVendorTypeId($query, $request);
                })
                ->when($request->latitude, function ($query) use ($request) {
                    return $this->filterByVendorLocation($query, $request);
                })
                */
                //filter by vendor type id
                ->when($request->vendor_type_id, function ($query) use ($request) {
                    return $query->whereHas('vendor', function ($query) use ($request) {
                        return $query->active()->where('vendor_type_id', $request->vendor_type_id);
                    });
                })
                //filter by vendor location
                ->when($request->latitude, function ($query) use ($request) {
                    if (!fetchDataByLocation()) {
                        return $query;
                    }
                    //
                    $latitude = $request->latitude;
                    $longitude = $request->longitude;
                    $deliveryZonesIds = $this->getDeliveryZonesByLocation($latitude, $longitude);
                    //where has vendors that has delivery zones
                    return $query->whereHas("vendor", function ($query) use ($deliveryZonesIds) {
                        $query->whereHas('delivery_zones', function ($query) use ($deliveryZonesIds) {
                            $query->whereIn('delivery_zone_id', $deliveryZonesIds);
                        });
                    });
                })
                ->paginate($this->productsPerPage);



            //
        } else if ($request->type == "service") {

            $services = $searchResult = Service::active()
                ->when($request->type == "best", function ($query) {
                    return $query->withCount('sales')->orderBy('sales_count', 'DESC');
                })
                ->when($request->keyword, function ($query) use ($request) {
                    // return $query->where('name', "like", "%" . $request->keyword . "%");
                    return $query->whereRaw('name LIKE ?', ["%$request->keyword%"]);
                })
                ->when(!empty($request->category_id), function ($query) use ($request) {
                    return $query->where('category_id', "=", $request->category_id);
                })
                ->when(!empty($request->subcategory_id), function ($query) use ($request) {
                    return $query->where('subcategory_id', "=", $request->subcategory_id);
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
                    return $query->where('vendor_id', $request->vendor_id);
                })
                ->when($request->sort, function ($query) use ($request) {
                    return $query->orderBy('name', $request->sort);
                })
                ->when($request->min_price, function ($query) use ($request) {
                    return $query->where('price', ">=", $request->min_price);
                })
                ->when($request->max_price, function ($query) use ($request) {
                    return $query->where('price', "<=", $request->max_price);
                })
                ->when($request->latitude, function ($query) use ($request) {
                    return $query->where(function ($query) use ($request) {
                        return $query->whereHas('vendor', function ($query) use ($request) {
                            return $query->active()->within($request->latitude, $request->longitude);
                        })->orWhereHas('vendor', function ($query) use ($request) {
                            return $query->active()->withinrange($request->latitude, $request->longitude);
                        });
                    });
                })
                ->paginate($this->productsPerPage);
        } else {
            //
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            $vendors = $searchResult = Vendor::active()
                ->when($request->keyword, function ($query) use ($request) {
                    // return $query->where('name', "like", "%" . $request->keyword . "%");
                    return $query->whereRaw('name LIKE ?', ["%$request->keyword%"]);
                })
                ->when($request->is_open, function ($query) use ($request) {
                    return $query->where('is_open', "=", $request->is_open);
                })
                ->when($request->category_id, function ($query) use ($request) {
                    return $query->whereHas("categories", function ($query) use ($request) {
                        return $query->where('category_id', "=", $request->category_id);
                    });
                })
                // subcategory_id
                ->when($request->subcategory_id, function ($query) use ($request) {
                    return $query->whereHas("categories", function ($query) use ($request) {
                        return $query->whereHas('sub_categories', function ($query) use ($request) {
                            return $query->where('subcategories.id', "=", $request->subcategory_id);
                        });
                    });
                })
                ->when($request->vendor_type_id, function ($query) use ($request) {
                    return $query->where('vendor_type_id', "=", $request->vendor_type_id);
                })
                ->when($request->sort, function ($query) use ($request) {
                    return $query->orderBy('name', $request->sort);
                })
                ->when($request->latitude, function ($query) use ($request) {
                    return $query->where(function ($query) use ($request) {
                        return $query->where(function ($query) use ($request) {
                            return $query->within($request->latitude, $request->longitude);
                        })->orwhere(function ($query) use ($request) {
                            return $query->withinrange($request->latitude, $request->longitude);
                        });
                    });
                })
                ->orderBy('is_open', 'desc')
                ->paginate();
        }

        //
        if (!$request->has('merge')) {
            return response()->json([
                "products" => $products,
                "vendors" => $vendors,
                "services" => $services,
            ], 200);
        } else {
            return response()->json($searchResult, 200);
        }
    }


    function filterByVendorTypeId($query, $request)
    {
        return $query->whereHas("vendor", function ($query) use ($request) {
            return $query->where('vendor_type_id', $request->vendor_type_id);
        });
    }

    function filterByVendorLocation($query, $request)
    {
        return $query->whereHas('vendor', function ($query) use ($request) {
            return $query->active()->within($request->latitude, $request->longitude);
        })->orWhereHas('vendor', function ($query) use ($request) {
            return $query->active()->withinrange($request->latitude, $request->longitude);
        });
    }

    function filterByVendorChecks($query, $request)
    {
        return $query->whereHas('vendor', function ($query) use ($request) {
            return $query->active()
                ->within($request->latitude, $request->longitude)
                ->where('vendor_type_id', $request->vendor_type_id);
        })->orWhereHas('vendor', function ($query) use ($request) {
            return $query->active()
                ->withinrange($request->latitude, $request->longitude)
                ->where('vendor_type_id', $request->vendor_type_id);
        });
    }
}