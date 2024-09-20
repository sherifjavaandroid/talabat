<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\SubscriptionVendor;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class ProductObserver
{

    public function creating(Product $model)
    {
        //set default for missing values
        if ($model->discount_price == null) {
            $model->discount_price = 0;
        }

        //check if vendor uses subscription
        $vendor = Vendor::find($model->vendor_id);
        //
        if (!empty($vendor) && $vendor->use_subscription) {

            //get vendor subscription
            if ($vendor->has_subscription) {
                $vendorSubscription = SubscriptionVendor::active()->with('subscription')->where('vendor_id', $vendor->id)->first();
                //check if the subscription have qty set
                if (!empty($vendorSubscription->subscription->qty)) {
                    //
                    $totalProducts = Product::where('vendor_id', $vendor->id)->count();
                    //if products that vendor has is more or equals to qty
                    if ($vendorSubscription->subscription->qty <= $totalProducts) {
                        throw new \Exception(__("Vendor reached maximum allow items for current subscription"), 1);
                    }
                } else {
                    //qty not set, so continue
                }
            } else {
                throw new \Exception(__("Vendor requires subscription"), 1);
            }
        }

        //set approved to true if user has admin role or auto-approve-product permission
        $this->handleProductApproval($model);
    }

    public function updating(Product $model)
    {
        //set approved to true if user has admin role or auto-approve-product permission
        $this->handleProductApproval($model);
    }


    public function handleProductApproval(Product $model)
    {
        //ingore if from console
        if (app()->runningInConsole()) {
            $model->approved = true;
            return;
        }
        //check to see if only product available_qty is updated
        $changedColumns = $model->getDirty();
        if (count($changedColumns) == 1 && array_key_exists('available_qty', $changedColumns)) {
            return;
        }


        //if not permission named: auto-approve-product
        $checkPermission = Permission::where('name', 'auto-approve-product')->first();
        if (empty($checkPermission)) {
            $model->approved = true;
            return;
        }

        //
        $productDetailsUpdateRequest = (bool) setting('productDetailsUpdateRequest', 0);
        if (!$productDetailsUpdateRequest) {
            $model->approved = true;
            return;
        }
        //
        //ge user from api guard or web guard
        $user = auth()->user();
        if (empty($user)) {
            $user = auth('api')->user();
        }

        //
        $userModel = User::find($user->id);

        //set approved to true if user has admin role or auto-approve-product permission
        if ($userModel->hasRole('admin') || $userModel->hasPermissionTo('auto-approve-product')) {
            $model->approved = true;
        } else {
            $model->approved = false;
        }
    }
}
