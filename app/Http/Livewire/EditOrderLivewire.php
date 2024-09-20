<?php

namespace App\Http\Livewire;

use App\Models\Option;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class EditOrderLivewire extends BaseLivewireComponent
{

    public $showSummary = false;
    public $vendorID;
    public $vendor;
    public $product;
    public $productIDs = [];
    public $paymentMethods;
    public $editOrderProducts;
    public $editProductOptions = [];
    public $editProductSelectedOptions;
    public $editOrderProductsQtys;
    public $coupon;
    public $products;
    public $productSearchClause = ['vendor_id' => 0];

    protected $listeners = [
        'showEditProducts' => 'showEditProducts',
        // 'showEditModal' => 'showEditModal',
        'removeProductFromOrder' => 'removeProductFromOrder',
        'dismissModal' => 'dismissModal',
        'refreshView' => '$refresh',
        'autocompleteProductSelected' => 'autocompleteProductSelected',
    ];

    public function mount($code)
    {
        $this->selectedModel = Order::whereCode($code)->first();
        $this->productSearchClause = ['vendor_id' => $this->selectedModel->vendor_id];
        $this->emit('productQueryClasueUpdate', $this->productSearchClause);
        $this->preloadCurrentOrderProducts();
    }

    public function render()
    {
        return view('livewire.edit-order');
    }




    public function preloadCurrentOrderProducts()
    {
        //
        $this->selectedModel = Order::find($this->selectedModel["id"]);
        foreach ($this->selectedModel->products as $key => $orderProduct) {
            $this->autocompleteProductSelected($orderProduct->product);
            //populate qty
            $this->editOrderProductsQtys[$orderProduct->product_id] = $orderProduct->quantity;
            //populate selected options
            if (!empty($orderProduct->options_ids)) {
                $orderProductOptions = explode(",", $orderProduct->options_ids);
                $options = Option::whereIn('id', $orderProductOptions)->get();
                foreach ($options as $key => $option) {
                    if ($option->option_group->multiple) {
                        $this->editProductOptions[$orderProduct->product_id][$option->option_group_id][$option->id] = true;
                    } else {
                        $this->editProductOptions[$orderProduct->product_id][$option->option_group_id] = true;
                    }
                }
            }
        }
    }



    public function autocompleteProductSelected($product)
    {
        try {

            if (empty($this->productIDs)) {
                $this->productIDs = [];
            }

            //
            $productId = $product['id'];
            $editProductIDs = $this->productIDs;
            if (!is_array($editProductIDs)) {
                $editProductIDs = [];
            }
            //if product already exists
            if (!in_array($productId, $editProductIDs)) {
                array_push($editProductIDs, $productId);
            }
            $this->productIDs = $editProductIDs;
            $this->editOrderProducts = Product::whereIn('id', $this->productIDs)->get();

            //
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }

    public function removeProductFromOrder($id)
    {

        //

        $this->editOrderProducts = $this->editOrderProducts->reject(function ($element) use ($id) {

            return $element->id == $id;
        });
        //
        $this->productIDs = $this->editOrderProducts->pluck('id') ?? [];
        $this->editOrderProductsQtys[$id] = null;
    }


    public function updateOrderProducts()
    {

        if (empty($this->productIDs)) {
            $this->showErrorAlert(__("Please check at least one product"));
            return;
        }



        $this->validate([
            'editOrderProductsQtys.*' => 'sometimes|nullable|numeric|min:1',
        ], [
            'editOrderProductsQtys.*' => __('Qty is required'),
        ]);

        try {

            //only allow cod payment edit orders
            if ($this->selectedModel->payment_method != null && !$this->selectedModel->payment_method->is_cash) {
                throw new \Exception(__("Only Order with Cash Payment can be edited. Thank you"), 1);
            }
            DB::beginTransaction();
            //clear old order products
            $this->selectedModel->products()->delete();

            //
            foreach ($this->editOrderProducts as $editOrderProduct) {
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $this->selectedModel->id;
                $orderProduct->quantity = ($this->editOrderProductsQtys[$editOrderProduct->id] ?? 1);
                $orderProduct->price = ($editOrderProduct->discount_price <= 0) ? $editOrderProduct->price : $editOrderProduct->discount_price;
                $orderProduct->product_id = $editOrderProduct->id;

                //flatten options
                $productOptionsString = "";
                $productOptionsIds = "";
                //
                $productOptions = Option::whereHas('products', function ($query) use ($editOrderProduct) {
                    return $query->where('product_id', "=", $editOrderProduct->id);
                })->get();
                $foundCount = 0;
                foreach ($productOptions as $productOption) {
                    $optionSelected = false;
                    try {
                        if ($productOption->option_group->multiple) {
                            $optionSelected = $this->editProductOptions[$orderProduct->product_id][$productOption->option_group_id][$productOption->id];
                        } else {
                            $optionSelected = $this->editProductOptions[$orderProduct->product_id][$productOption->option_group_id];
                        }
                    } catch (\Exception $ex) {
                        logger("edit product with option error", [$ex]);
                    }

                    //
                    if ($optionSelected) {
                        $productOptionsString .= $productOption->name;
                        $productOptionsIds .= $productOption->id;
                        if ($foundCount > 0) {
                            $productOptionsString .= ", ";
                            $productOptionsIds .= ",";
                        }
                        $foundCount++;
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


            //update order price
            $subtotal = $this->getOrderSubtotal();
            $this->selectedModel->sub_total = $subtotal;
            $this->selectedModel->total = $subtotal - $this->selectedModel->discount + $this->selectedModel->tax + $this->selectedModel->tip + $this->selectedModel->delivery_fee;
            $this->selectedModel->save();

            DB::commit();
            $this->showSuccessAlert(__("Order update successfully!"));
            $this->emit('clearAutocompleteFieldsEvent');
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            DB::rollback();
            $this->showErrorAlert($ex->getMessage() ?? __("Order update failed!"));
        }
    }


    //get order
    public function getOrderSubtotal()
    {

        $order = $this->selectedModel;
        $order->sub_total = 0;

        //
        foreach ($this->editOrderProducts as $key => $editPrderProduct) {
            if ($editPrderProduct->discount_price > 0) {
                $productPrice = $editPrderProduct->discount_price;
            } else {
                $productPrice = $editPrderProduct->price;
            }
            $order->sub_total += $productPrice * ($this->editOrderProductsQtys[$editPrderProduct->id] ?? 1);
        }

        foreach ($this->editProductOptions ?? [] as $key => $editProductOptionObject) {

            $optionIdsArray = [];
            foreach ($editProductOptionObject as $editProductOptionObjectValues) {

                //
                if (gettype($editProductOptionObjectValues) == 'array') {
                    foreach ($editProductOptionObjectValues as $key3 => $editProductOptionObjectValue) {
                        if ($editProductOptionObjectValue) {
                            array_push($optionIdsArray, $key3);
                        }
                    }
                } else {
                    array_push($optionIdsArray, $editProductOptionObjectValues);
                }
            }

            $selectedProductOptions = Option::whereIn('id', $optionIdsArray)->get();
            $this->editProductSelectedOptions[$key] = $selectedProductOptions;

            //pricing
            foreach ($selectedProductOptions as $selectedProductOption) {
                $order->sub_total += $selectedProductOption->price;
            }
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
                foreach ($this->editOrderProducts as $key => $editPrderProduct) {
                    if ($editPrderProduct->discount_price > 0) {
                        $productPrice = $editPrderProduct->discount_price;
                    } else {
                        $productPrice = $editPrderProduct->price;
                    }
                    //if the current product in loop is in the products coupon can be applied on
                    if (in_array($editPrderProduct->id, $couponProductsIds)) {
                        if ($this->coupon->percentage) {
                            $order->discount += $productPrice * ($this->coupon->discount / 100);
                        } else {
                            $order->discount += $productPrice * $this->coupon->discount;
                        }
                    }
                }
            } else if (count($couponVendors) > 0) {
                //check if vendor is part of listed vendors coupon can be applied
                if (in_array($this->editPrder->vendor_id, $couponVendorsIds)) {
                    if ($this->coupon->percentage) {
                        $order->discount = $order->sub_total * ($this->coupon->discount / 100);
                    } else {
                        $order->discount = $order->sub_total * $this->coupon->discount;
                    }
                }
            } else {
                $order->discount = 0;
            }
        } else {
            $order->discount = 0;
        }

        return number_format($order->sub_total, 2, '.', '');
    }
}
