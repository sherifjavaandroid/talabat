<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\DeliveryAddress;
use App\Models\Option;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceOption;
use App\Traits\GoogleMapApiTrait;
use Illuminate\Http\Request;


class RegularOrderController extends Controller
{
    use GoogleMapApiTrait;
    //
    public function deliveryFeeSummary(Request $request)
    {


        //delivery_address_id
        //vendor_id

        //
        $vendor = Vendor::find($request->vendor_id);

        //vendor has delivery_zones for delivery fee from zones
        if ($vendor != null && $vendor->delivery_zones->count() > 0) {

            //
            if ($request->delivery_address_id != "null" && !empty($request->delivery_address_id) && isset($request->delivery_address_id)) {
                $deliveryAddressLocation = $this->getDeliveryAddress($request->delivery_address_id);
                $destinationLatLngs = "" . $deliveryAddressLocation->latitude . "," . $deliveryAddressLocation->longitude;
            } else {
                $destinationLatLngs = $request->latlng;
            }

            //
            $deliveryZones = $vendor->delivery_zones;
            $distanceAmount = null;
            $latitude = explode(",", $destinationLatLngs)[0];
            $longitude = explode(",", $destinationLatLngs)[1];
            $cLatLng = [
                'lat' => $latitude,
                'lng' => $longitude
            ];
            //find the delivery zone that provided delivery address is within
            foreach ($deliveryZones as $deliveryZone) {
                $inBound = $this->insideBound($cLatLng, $deliveryZone->points);
                if ($inBound && $deliveryZone->delivery_fee != null) {
                    $distanceAmount = $deliveryZone->delivery_fee;
                    break;
                }
            }


            //
            if ($distanceAmount != null) {
                return response()->json([
                    "delivery_fee" => $distanceAmount,
                ]);
            }
        }

        //previous delivery fee calculation
        if (setting('enableGoogleDistance', 0)) {

            //
            if ($request->delivery_address_id != "null" && !empty($request->delivery_address_id) && isset($request->delivery_address_id)) {
                $deliveryAddressLocation = $this->getDeliveryAddress($request->delivery_address_id);
                $destinationLatLngs = "" . $deliveryAddressLocation->latitude . "," . $deliveryAddressLocation->longitude;
            } else {
                $destinationLatLngs = $request->latlng;
            }
            //

            $originLatLng = "" . $vendor->latitude . "," . $vendor->longitude;

            //
            try {
                $deliveryLocationDistance = $this->getTotalDistanceFromGoogle(
                    $originLatLng,
                    $destinationLatLngs
                );
            } catch (\Exception $ex) {
                $deliveryLocationDistance = $this->getLinearDistance(
                    $originLatLng,
                    $destinationLatLngs
                );
            }

            //


        } else {
            //linear distance calculation
            $deliveryLocationDistance = DeliveryAddress::distance($vendor->latitude, $vendor->longitude)
                ->where('id', $request->delivery_address_id)
                ->first()
                ->distance;
        }


        //calculate the distance price
        if ($vendor->charge_per_km) {
            $distanceAmount = $vendor->delivery_fee * $deliveryLocationDistance;
        } else {
            $distanceAmount = $vendor->delivery_fee;
        }
        //
        $distanceAmount += $vendor->base_delivery_fee;

        return response()->json([
            "delivery_fee" => $distanceAmount,
        ]);
    }

    //
    public function summary(Request $request)
    {


        //delivery_address_id
        //vendor_id
        if ($request->delivery_address_id != null && $request->pickup == 0 && $request->delievryAddressOutOfRange == 0) {
            $deliveryFee = $this->deliveryFeeSummary($request)->getData()->delivery_fee;
        } else {
            $deliveryFee = 0;
        }
        $deliveryFeeDiscount = 0;

        //
        $vendor = Vendor::find($request->vendor_id);
        $subtotal = 0;
        $discount = 0;
        $tax = 0;
        $total = 0;
        //calculate subtotal
        $mProducts = [];
        $returnedProducts = [];
        foreach ($request->products ?? [] as $product) {
            //
            $productId = $product['product']['id'];
            $productModel = Product::findorfail($productId);
            $sellPrice = $productModel->sell_price;
            //price for possible selected options
            $optionIds = $product['options_ids'] ?? [];
            $options = Option::whereIn('id', $optionIds)->get();
            $totalOptionsPrice = $options->sum('price') ?? 0;
            //
            $noOptions = $productModel->options->count() == 0;
            if ($noOptions) {
                $productPrice = $sellPrice;
            } else {
                //check if product
                if ($productModel->plus_option ?? false || empty($options)) {
                    $productPrice = $sellPrice + $totalOptionsPrice;
                } else if (empty($options)) {
                    $productPrice = $sellPrice;
                } else {
                    $productPrice = $totalOptionsPrice;
                }
            }




            $productPrice = $productPrice * ($product['selected_qty'] ?? 1);
            $subtotal += $productPrice;
            //
            $mProducts[] = [
                "product" => $product['product'],
                "id" => $productModel->id,
                "selected_qty" => $product['selected_qty'] ?? 1,
                "price" => $productPrice,
                "sell_price" => $sellPrice,
                "options" => $product['options'] ?? [],
                "options_ids" => $product['options_ids'] ?? [],
                "options_flatten" => $product['options_flatten'] ?? "",
                "options_price" => $totalOptionsPrice,
                "product_price" => $sellPrice,
            ];
            //
            $returnedProducts[] = [
                "product" => $productModel,
                "id" => $productModel->id,
                "selected_qty" => $product['selected_qty'] ?? 1,
                "price" => $productPrice,
                "sell_price" => $sellPrice,
                "options" => $product['options'] ?? [],
                "options_ids" => $product['options_ids'] ?? [],
                "options_flatten" => $product['options_flatten'] ?? "",
                "options_price" => $totalOptionsPrice,
                "product_price" => $sellPrice,
            ];
        }

        //calculate discount
        $coupon = Coupon::where('code', $request->coupon_code)->first();
        if ($coupon != null) {
            //apply the coupon
            //if coupon is for delivery fee
            if ($coupon->for_delivery) {
                if ($coupon->percentage) {
                    $deliveryFeeDiscount = ($coupon->discount / 100) * $deliveryFee;
                } else {
                    $deliveryFeeDiscount = $coupon->discount;
                }
                //check for max_coupon_amount
                if ($coupon->max_coupon_amount != null && $coupon->max_coupon_amount < $deliveryFeeDiscount) {
                    $deliveryFeeDiscount = $coupon->max_coupon_amount;
                }
                //cap at 0
                if ($deliveryFeeDiscount < 0) {
                    $deliveryFeeDiscount = 0;
                }
                //apply discount
                $deliveryFee -= $deliveryFeeDiscount;
            } else {

                //
                $vendorIds = $coupon->vendors->pluck('id')->toArray();
                $isVendorSpecific = !empty($vendorIds) && empty($coupon->products);
                $productIds = $coupon->products->pluck('id')->toArray() ?? [];
                $isProductSpecific = !empty($productIds);
                $isVendorTypeSpecific = $coupon->vendor_type_id != null;

                //for vendor specific coupon
                if ($isVendorSpecific) {
                    //
                    $vendorMatch = in_array($vendor->id, $vendorIds);
                    if ($vendorMatch) {
                        if ($coupon->percentage) {
                            $discount = ($coupon->discount / 100) * $subtotal;
                        } else {
                            $discount = $coupon->discount;
                        }
                        //check for max_coupon_amount
                        if ($coupon->max_coupon_amount != null && $coupon->max_coupon_amount < $discount) {
                            $discount = $coupon->max_coupon_amount;
                        }
                        //check for min_order_amount
                        if ($coupon->min_order_amount != null && $coupon->min_order_amount > $subtotal) {
                            $discount = 0;
                        }
                    } else {
                        $discount = 0;
                    }

                    //cap at 0
                    if ($discount < 0) {
                        $discount = 0;
                    }
                }
                //for product specific coupon
                else if ($isProductSpecific) {
                    //loop through products and apply discount on each product that has the coupon
                    foreach ($mProducts as $mProduct) {
                        $mProductId = $mProduct['product']['id'];
                        $productMatch = in_array($mProductId, $productIds);
                        if ($productMatch) {
                            $productModel = Product::findorfail($mProductId);
                            if ($coupon->percentage) {
                                $discount += ($coupon->discount / 100) * $productModel->sell_price;
                            } else {
                                $discount += $coupon->discount;
                            }
                            //check for max_coupon_amount
                            if ($coupon->max_coupon_amount != null && $coupon->max_coupon_amount < $discount) {
                                $discount += $coupon->max_coupon_amount;
                            }
                            //check for min_order_amount
                            if ($coupon->min_order_amount != null && $coupon->min_order_amount > $subtotal) {
                                $discount += 0;
                            }
                        } else {
                            $discount += 0;
                        }
                    }
                    //cap at 0
                    if ($discount < 0) {
                        $discount = 0;
                    }
                }
                //if coupon is for vendor type
                else if ($isVendorTypeSpecific) {
                    //
                    $vendorTypeMatch = $coupon->vendor_type_id == $vendor->vendor_type_id;
                    if ($vendorTypeMatch) {
                        if ($coupon->percentage) {
                            $discount = ($coupon->discount / 100) * $subtotal;
                        } else {
                            $discount = $coupon->discount;
                        }
                        //check for max_coupon_amount
                        if ($coupon->max_coupon_amount != null && $coupon->max_coupon_amount < $discount) {
                            $discount = $coupon->max_coupon_amount;
                        }
                        //check for min_order_amount
                        if ($coupon->min_order_amount != null && $coupon->min_order_amount > $subtotal) {
                            $discount = 0;
                        }
                    } else {
                        $discount = 0;
                    }

                    //cap at 0
                    if ($discount < 0) {
                        $discount = 0;
                    }
                }
                //if there is not restriction
                else {
                    if ($coupon->percentage) {
                        $discount = ($coupon->discount / 100) * $subtotal;
                    } else {
                        $discount = $coupon->discount;
                    }
                    //check for max_coupon_amount
                    if ($coupon->max_coupon_amount != null && $coupon->max_coupon_amount < $discount) {
                        $discount = $coupon->max_coupon_amount;
                    }
                    //check for min_order_amount
                    if ($coupon->min_order_amount != null && $coupon->min_order_amount > $subtotal) {
                        $discount = 0;
                    }

                    //cap at 0
                    if ($discount < 0) {
                        $discount = 0;
                    }
                }
            }
        }

        //calculate tax
        $vendorTax = $vendor->tax;
        $vendorTax ??= setting('finance.generalTax', 0);
        $tax = ($vendorTax / 100) * $subtotal;

        //calculate fees
        $fees = $vendor->fees ?? [];
        $mFees = [];
        $totalFee = 0;
        foreach ($fees as $fee) {
            $feeAmount = 0;
            if ($fee->percentage) {
                $feeAmount = ($fee->value / 100) * $subtotal;
            } else {
                $feeAmount = $fee->value;
            }
            $totalFee += $feeAmount;
            //
            $mFees[] = [
                "name" => $fee->name,
                "amount" => $feeAmount,
                "value" => $feeAmount,
                "id" => $fee->id,
            ];
        }

        //total
        $total = ($subtotal - $discount) + $deliveryFee + $tax + $totalFee;
        $totalWithTip = $total + ($request->tip ?? 0);

        //
        $summary = [
            "vendor_id" => $request->vendor_id,
            "delivery_fee" => $deliveryFee,
            "delivery_discount" => $deliveryFeeDiscount,
            "sub_total" => $subtotal,
            "subtotal" => $subtotal,
            "discount" => $discount,
            "tax" => $tax,
            "total" => $total,
            "total_with_tip" => $totalWithTip,
            'tip' => $request->tip ?? 0,
            "fees" => $mFees,
            "total_fee" => $totalFee,
            "products" => $mProducts ?? [],
        ];
        $summaryToken = encrypt($summary);
        $summary['token'] = $summaryToken;
        //replace the products with the one from the request
        $summary['products'] = $returnedProducts;
        //
        return response()->json($summary);
    }

    //
    public function serviceSummary(Request $request)
    {
        //
        $vendor = Vendor::find($request->vendor_id);
        $subtotal = 0;
        $discount = 0;
        $tax = 0;
        $deliveryFee = 0;
        $total = 0;
        //service
        $service = Service::find($request->service_id);
        $subtotal += $service->sell_price;
        //add selected options
        $optionIds = $request->options_ids ?? [];
        $options = ServiceOption::whereIn('id', $optionIds)->get();
        $optionsFlatten = $options->pluck('name')->implode(", ");
        $totalOptionsPrice = $options->sum('price');
        $subtotal += $totalOptionsPrice;
        //qty
        $subtotal *= $request->qty ?? 1;
        //delivery fee
        if ($request->has('delivery_address_id')) {
            if ($request->delivery_address_id != null) {
                $deliveryFee = $this->deliveryFeeSummary($request)->getData()->delivery_fee;
            } else {
                $deliveryFee = 0;
            }
        }
        //
        $mService = [
            "id" => $service->id,
            "price" => $service->price,
            "sell_price" => $service->sell_price,
            "options" => $options ?? [],
            "options_ids" => $optionIds ?? [],
            "options_flatten" => $optionsFlatten ?? "",
            "options_price" => $totalOptionsPrice ?? 0,
            "service_price" => $totalOptionsPrice ?? 0,
            "qty" => $request->qty ?? 1,
        ];
        //calculate discount
        $coupon = Coupon::where('code', $request->coupon_code)->first();
        if ($coupon != null) {
            //apply the coupon
            if (!$coupon->for_delivery) {

                //
                $vendorIds = $coupon->vendors->pluck('id')->toArray();
                $isVendorSpecific = !empty($vendorIds) && empty($coupon->products);
                $isVendorTypeSpecific = $coupon->vendor_type_id != null;

                //for vendor specific coupon
                if ($isVendorSpecific) {
                    //
                    $vendorMatch = in_array($vendor->id, $vendorIds);
                    if ($vendorMatch) {
                        if ($coupon->percentage) {
                            $discount = ($coupon->discount / 100) * $subtotal;
                        } else {
                            $discount = $coupon->discount;
                        }
                        //check for max_coupon_amount
                        if ($coupon->max_coupon_amount != null && $coupon->max_coupon_amount < $discount) {
                            $discount = $coupon->max_coupon_amount;
                        }
                        //check for min_order_amount
                        if ($coupon->min_order_amount != null && $coupon->min_order_amount > $subtotal) {
                            $discount = 0;
                        }
                    } else {
                        $discount = 0;
                    }

                    //cap at 0
                    if ($discount < 0) {
                        $discount = 0;
                    }
                }
                //if coupon is for vendor type
                else if ($isVendorTypeSpecific) {
                    //
                    $vendorTypeMatch = $coupon->vendor_type_id == $vendor->vendor_type_id;
                    if ($vendorTypeMatch) {
                        if ($coupon->percentage) {
                            $discount = ($coupon->discount / 100) * $subtotal;
                        } else {
                            $discount = $coupon->discount;
                        }
                        //check for max_coupon_amount
                        if ($coupon->max_coupon_amount != null && $coupon->max_coupon_amount < $discount) {
                            $discount = $coupon->max_coupon_amount;
                        }
                        //check for min_order_amount
                        if ($coupon->min_order_amount != null && $coupon->min_order_amount > $subtotal) {
                            $discount = 0;
                        }
                    } else {
                        $discount = 0;
                    }

                    //cap at 0
                    if ($discount < 0) {
                        $discount = 0;
                    }
                }
                //if there is not restriction
                else {
                    if ($coupon->percentage) {
                        $discount = ($coupon->discount / 100) * $subtotal;
                    } else {
                        $discount = $coupon->discount;
                    }
                    //check for max_coupon_amount
                    if ($coupon->max_coupon_amount != null && $coupon->max_coupon_amount < $discount) {
                        $discount = $coupon->max_coupon_amount;
                    }
                    //check for min_order_amount
                    if ($coupon->min_order_amount != null && $coupon->min_order_amount > $subtotal) {
                        $discount = 0;
                    }

                    //cap at 0
                    if ($discount < 0) {
                        $discount = 0;
                    }
                }
            }
        }

        //calculate tax
        $vendorTax = $vendor->tax;
        $vendorTax ??= setting('finance.generalTax', 0);
        $tax = ($vendorTax / 100) * $subtotal;

        //calculate fees
        $fees = $vendor->fees ?? [];
        $mFees = [];
        $totalFee = 0;
        foreach ($fees as $fee) {
            $feeAmount = 0;
            if ($fee->percentage) {
                $feeAmount = ($fee->value / 100) * $subtotal;
            } else {
                $feeAmount = $fee->value;
            }
            $totalFee += $feeAmount;
            //
            $mFees[] = [
                "name" => $fee->name,
                "amount" => $feeAmount,
                "value" => $feeAmount,
                "id" => $fee->id,
            ];
        }

        //total
        $total = ($subtotal - $discount) + $deliveryFee + $tax + $totalFee;
        $totalWithTip = $total + ($request->tip ?? 0);

        //
        $summary = [
            "vendor_id" => $request->vendor_id,
            "sub_total" => $subtotal,
            "subtotal" => $subtotal,
            "discount" => $discount,
            "delivery_fee" => $deliveryFee,
            "tax" => $tax,
            "total" => $total,
            "total_with_tip" => $totalWithTip,
            'tip' => $request->tip ?? 0,
            "fees" => $mFees,
            "total_fee" => $totalFee,
            "service" => $mService,
        ];
        $summaryToken = encrypt($summary);
        $summary['token'] = $summaryToken;
        //
        return response()->json($summary);
    }



    //
    public function getDeliveryAddress($id): DeliveryAddress
    {
        return DeliveryAddress::find($id);
    }
}