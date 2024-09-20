<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class SignedMediaController extends Controller
{
    //
    public function download(Request $request, $id)
    {
        $product = Product::find($id);
        $authUser = \Auth::guard('api')->user();
        if ($request->auth) {

            try {
                $decrypted = \Crypt::decrypt($request->auth);
                $authUser = User::find($decrypted['user_id']);
            } catch (\DecryptException $e) {
                return response()->json([
                    "message" => __("Download Link seems to have been compromised. Please again with a new link")
                ], 401);
            }
        }

        //
        if (empty($authUser)) {
            return $this->unauthorise();
        }
        //admin role
        if ($authUser->hasRole('admin')) {
            return $this->downloadFiles($product);
        }
        //check if the user have manager role to download the files
        if ($authUser->hasRole(['manager', 'admin'])) {
            if ($product->vendor_id == $authUser->vendor_id) {
                return $this->downloadFiles($product);
            }
            return $this->unauthorise();
        }
        //client user
        if ($authUser->hasRole('client')) {
            $order = Order::wherePaymentStatus("successful")
                ->whereUserId($authUser->id)
                ->whereVendorId($product->vendor_id)
                ->whereHas('products', function ($q) use ($product) {
                    return $q->where('product_id', $product->id);
                })->first();


            if (!empty($order)) {
                return $this->downloadFiles($product);
            }else{
                return $this->unauthorise();
            }

        }
        return $this->unauthorise();
    }

    public function downloadFiles($product)
    {
        $files = \Storage::allFiles("{$product->digitalFilePath}/{$product->id}");
        return \Storage::download($files[0]);
    }

    public function unauthorise()
    {
        return response()->json([
            "message" => __("Unauthorised Access")
        ], 401);
    }
}
