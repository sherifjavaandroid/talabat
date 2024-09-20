<?php

namespace App\Http\Livewire;

use App\Models\Coupon;
use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use App\Models\CouponUser;
use App\Models\OptionGroup;
use App\Models\Option;
use App\Models\Vendor;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Twilio\TwiML\Voice\Pay;

class NewOrderLivewire extends BaseLivewireComponent
{

    public $vendor;
    public $products = [];
    public $newOrder;
    public $model = Order::class;
    public $selectedProductId;

    public $vendor_id;
    public $user_id;
    public $optionGroups;
    public $newProductSelectedOptions = [];
    public $delivery_address_id;
    public $payment_method_id;
    public $isPickup;
    public $tip;
    public $note;
    public $coupon;
    public $coupon_code;


    public $paymentMethods;
    public $newOrderProducts;
    public $newProductOptions = [];
    public $newOrderProductsQtys;
    public $showSummary = false;

    public function getListeners()
    {
        return $this->listeners +
            [
                'productsUpdated' => 'setProductsUpdated',
                'vendorUpdated' => 'setVendorUpdated',
                'user_idUpdated' => 'userIdUpdated',
                'delivery_address_idUpdated' => 'deliveryAddressIdUpdated',
                'payment_method_idUpdated' => 'paymentMethodIdUpdated',
            ];
    }


    public function render()
    {
        return view('livewire.new-order');
    }


    public function setVendorUpdated($id)
    {
        $this->vendor = Vendor::find($id);
    }

    public function setProductsUpdated($value)
    {
        $this->addProduct($value);
    }

    public function userIdUpdated($value)
    {
        $this->user_id = $value['value'];
    }

    public function deliveryAddressIdUpdated($value)
    {
        $this->delivery_address_id = $value['value'];
    }

    public function paymentMethodIdUpdated($value)
    {
        $this->payment_method_id = $value['value'];
    }



    public function addProduct($id)
    {
        try {

            if (empty($this->products)) {
                $this->products = [];
            }
            //if not array
            if (!is_array($this->products)) {
                $this->products = [];
            }


            //
            $this->selectedProductId = $id;
            $this->selectedModel = Product::find($id);


            //if product has options then show options
            if ($this->selectedModel->options->count() > 0) {
                $this->optionGroups = $this->selectedModel->option_groups;
                $this->showDetailsModal(null);
                return;
            }




            // $this->products[] = [
            //     'product_id' => $id,
            //     'product' => $this->selectedModel,
            //     'selected_options' => "",
            //     'price' => $this->selectedModel->sell_price,
            //     'qty' => 1,
            // ];
            $mProduct = [
                'product_id' => $id,
                'name' => $this->selectedModel->name,
                'selected_options' => json_encode([]),
                'price' => $this->selectedModel->sell_price,
                'qty' => 1,
            ];
            //array push
            array_push($this->products, $mProduct);

            //
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }

    public function addOptionsToProduct()
    {

        $this->validate([
            'newProductSelectedOptions' => 'required',
        ]);

        //loop through the selected options
        $selectedOptionIds = [];
        foreach (collect($this->newProductSelectedOptions) as $key => $value) {
            if ((bool) $value) {
                $selectedOptionIds[] = $key;
            }
        }

        $this->selectedModel = Product::find($this->selectedProductId);
        $selectedOptions = Option::whereIn('id', $selectedOptionIds)->get();
        //map and transform
        $selectedOptions = $selectedOptions->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
            ];
        });
        //
        $mProduct = [
            'product_id' => $this->selectedModel->id,
            'name' => $this->selectedModel->name,
            'selected_options' => json_encode($selectedOptions),
            'price' => $this->selectedModel->sell_price,
            'qty' => 1,
        ];

        array_push($this->products, $mProduct);
        $this->newProductSelectedOptions = [];
        $this->showDetails = false;
    }

    public function removeProduct($key)
    {
        if ($key == 0 && count($this->products) == 1) {
            $this->showErrorAlert(__('Please select at least one(1) item\/product'));
        } else {
            unset($this->products[$key]);
            $this->products = array_values($this->products);
        }
    }

    public function applyCoupon()
    {
        $this->validate([
            'coupon_code' => 'required',
        ]);

        $this->coupon = Coupon::with('vendors', 'products')->active()->where('code', $this->coupon_code)->first();
        if ($this->coupon) {
            $this->showSuccessAlert(__('Coupon applied successfully'));
        } else {
            $this->showErrorAlert(__('Coupon not found'));
        }
    }


    public function showSummary()
    {
        //
        if (empty($this->vendor)) {
            $this->showErrorAlert(__("Please check Vendor"));
            return;
        } else if (empty($this->products)) {
            $this->showErrorAlert(__("Please check at least one product"));
            return;
        } else if (empty($this->user_id)) {
            $this->showErrorAlert(__("Please check customer"));
            return;
        } else if (!$this->isPickup && empty($this->delivery_address_id)) {
            $this->showErrorAlert(__("Please select delivery address"));
            return;
        } else if (!$this->isPickup) {

            //default delivery address
            $deliveryAddress = DeliveryAddress::distance($this->vendor->latitude, $this->vendor->longitude)->find($this->delivery_address_id);
            if ($deliveryAddress->distance > $this->vendor->delivery_range) {
                $distanceBetweenOrder = $deliveryAddress->distance;
                $msg = __("Delivery address is out of vendor delivery range");
                $msg .= ".\n ";
                $msg .= __("Distance between order and vendor is");
                $msg .= " $distanceBetweenOrder km";
                $msg .= ".\n ";
                $msg .= __("Vendor delivery range is :vendorDeliveryRange");
                $vendorDeliveryRange = $this->vendor->delivery_range;
                $msg .= " $vendorDeliveryRange km";
                $this->showErrorAlert($msg, null);
                return;
            }
        } else if (empty($this->payment_method_id)) {
            $this->showErrorAlert(__("Please select payment method"));
            return;
        }

        //
        $this->newOrder = $this->getOrderData();
        $this->showSummary = true;
    }



    public function saveNewOrder()
    {

        //
        try {
            DB::beginTransaction();
            $this->newOrder = $this->getOrderData();
            $this->newOrder->save();
            $this->newOrder->setStatus("pending");

            foreach ($this->products ?? [] as $key => $newOrderProductData) {
                $newOrderProduct = Product::find($newOrderProductData['product_id']);
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $this->newOrder->id;
                $orderProduct->quantity = ($newOrderProductData['qty'] ?? 1);
                $orderProduct->price = ($newOrderProduct->discount_price <= 0) ? $newOrderProduct->price : $newOrderProduct->discount_price;
                $orderProduct->product_id = $newOrderProduct->id;

                //flatten options
                $productOptionsString = "";
                $productOptionsIds = "";

                foreach ($this->products ?? [] as $key => $newOrderProductData) {
                    $productOptions = $newOrderProductData['selected_options'] ?? [];
                    if (is_string($productOptions)) {
                        $productOptions = json_decode($productOptions, true);
                    }
                    foreach ($productOptions as $key => $productOption) {
                        $productOptionsString .= $productOption['name'];
                        $productOptionsIds .= $productOption['id'];
                        if ($key < (count($productOptions) - 1)) {
                            $productOptionsString .= ", ";
                            $productOptionsIds .= ",";
                        }
                    }
                }
                //
                $orderProduct->options = $productOptionsString;
                $orderProduct->options_ids = $productOptionsIds;
                $orderProduct->save();

                //reduce product qty
                $product = $orderProduct->product;
                if (!empty($product->available_qty)) {
                    $product->available_qty = $product->available_qty - $orderProduct->quantity;
                    $product->save();
                }
            }

            //save the coupon used
            $coupon = Coupon::where("code", $this->coupon_code)->first();
            if (!empty($coupon)) {
                $couponUser = new CouponUser();
                $couponUser->coupon_id = $coupon->id;
                $couponUser->user_id = Auth::id();
                $couponUser->order_id = $this->newOrder->id;
                $couponUser->save();
            }

            //so apps can be notified
            $this->newOrder->updated_at = \Carbon\Carbon::now();
            $this->newOrder->save();

            DB::commit();
            $this->showSuccessAlert(__("New Order successfully!"));

            $this->showSummary = false;
            $this->reset();
            $this->emit('refreshTable');
        } catch (\Exception $ex) {
            DB::rollback();
            $this->showErrorAlert($ex->getMessage() ?? __("New Order failed!"));
        }
    }









    //get order
    public function getOrderData()
    {

        $deliveryFee = 0;
        $order = new Order();
        $order->vendor_id = $this->vendor_id ?? $this->vendor->id;
        $order->user_id = $this->user_id;
        $order->delivery_address_id = $this->delivery_address_id;
        if (empty($this->payment_method_id)) {
            $this->paymentMethods ??= PaymentMethod::active()->get();
            $order->payment_method_id = $this->paymentMethods->first()->id ?? PaymentMethod::first()->id;
        } else {
            $order->payment_method_id = $this->payment_method_id;
        }

        //cash payment
        // if ($order->payment_method->slug == "cash") {
        //     $order->payment_status = "successful";
        // }
        $order->tip = $this->tip;
        $order->note = $this->note;
        $order->created_at = Carbon::now();
        $order->updated_at = Carbon::now();

        //
        foreach ($this->products ?? [] as $key => $newOrderProductData) {
            $newOrderProduct = Product::find($newOrderProductData['product_id']);
            if ($newOrderProduct->discount_price > 0) {
                $productPrice = $newOrderProduct->discount_price;
            } else {
                $productPrice = $newOrderProduct->price;
            }
            //
            $qty = $newOrderProductData['qty'] ?? 1;
            $order->sub_total += $productPrice * $qty;
        }

        foreach ($this->products ?? [] as $key => $newOrderProductData) {
            $selectedProductOptions = $newOrderProductData['selected_options'];
            if (is_string($selectedProductOptions)) {
                $selectedProductOptions = json_decode($selectedProductOptions, true);
            }
            $this->newProductSelectedOptions[$key] = $selectedProductOptions ?? $newOrderProductData['selected_options'];
            //
            $qty = $newOrderProductData['qty'] ?? 1;
            $optionTotalPrice = 0;
            //pricing
            foreach ($selectedProductOptions as $selectedProductOption) {
                $optionTotalPrice += $selectedProductOption['price'];
            }
            //
            $order->sub_total += $optionTotalPrice;
        }


        //
        if (!empty($this->coupon)) {
            //
            $couponVendors = $this->coupon->vendors;
            $couponVendorsIds = $this->coupon->vendors->pluck('id')->toArray();
            $couponProducts = $this->coupon->products;
            $couponProductsIds = $this->coupon->products->pluck('id')->toArray();

            //apply discount directly to total order
            if (count($couponVendors) == 0 && count($couponProducts) == 0) {

                if ($this->coupon->percentage) {
                    $order->discount = $order->sub_total * ($this->coupon->discount / 100);
                } else {
                    $order->discount = $this->coupon->discount;
                }
            } else if (count($couponProducts) > 0) {
                //go through selected products
                foreach ($this->newOrderProducts as $key => $newOrderProduct) {
                    if ($newOrderProduct->discount_price > 0) {
                        $productPrice = $newOrderProduct->discount_price;
                    } else {
                        $productPrice = $newOrderProduct->price;
                    }
                    //if the current product in loop is in the products coupon can be applied on
                    if (in_array($newOrderProduct->id, $couponProductsIds)) {
                        if ($this->coupon->percentage) {
                            $order->discount += $productPrice * ($this->coupon->discount / 100);
                        } else {
                            $order->discount += $productPrice * $this->coupon->discount;
                        }
                    }
                }
            } else if (count($couponVendors) > 0) {
                //check if vendor is part of listed vendors coupon can be applied
                if (in_array($this->newOrder->vendor_id, $couponVendorsIds)) {
                    if ($this->coupon->percentage) {
                        $order->discount = $order->sub_total * ($this->coupon->discount / 100);
                    } else {
                        $order->discount = $order->sub_total * $this->coupon->discount;
                    }
                }
            } else {
                $order->discount = 0;
            }

            //double check to make sure the max amount is not exceeded
            if ($order->discount > $this->coupon->max_coupon_amount) {
                $order->discount = $this->coupon->max_coupon_amount;
            }
        } else {
            $order->discount = 0;
        }


        //delivery fee
        if (!$this->isPickup) {
            $vendor = Vendor::find($this->vendor_id ?? $this->vendor->id);
            $deliveryAddress = DeliveryAddress::distance($vendor->latitude, $vendor->longitude)->find($this->delivery_address_id);

            $deliveryFee = $vendor->base_delivery_fee;
            $deliveryFee += $vendor->charge_per_km ? ($vendor->delivery_fee * $deliveryAddress->distance) : $vendor->delivery_fee;
        }

        $order->sub_total = number_format($order->sub_total, 2, '.', '');
        $order->delivery_fee = number_format($deliveryFee, 2, '.', '');
        $order->discount = number_format($order->discount, 2, '.', '');
        $order->tip = number_format($order->tip, 2, '.', '');
        $order->tax = number_format($order->sub_total * ($order->vendor->tax / 100), 2, '.', '');
        $order->total = $order->sub_total - $order->discount + $order->tax + $order->tip + $order->delivery_fee;
        return $order;
    }
}
