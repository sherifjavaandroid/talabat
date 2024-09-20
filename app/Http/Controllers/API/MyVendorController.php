<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VendorManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyVendorController extends Controller
{

    public function index(Request $request)
    {
        $vendorIds = VendorManager::where('user_id', Auth::id())->get()->pluck('vendor_id');
        $vendors = Vendor::whereIn('id', $vendorIds)->get();
        return response()->json($vendors);
    }


    public function switchVendor(Request $request)
    {
        $vendorId = $request->vendor_id;
        try {
            DB::beginTransaction();
            $user = User::find(Auth::id());
            $user->vendor_id = $vendorId;
            $user->save();
            DB::commit();
            return response()->json(
                [
                    'message' => __('Current vendor updated successfully!')
                ]
            );
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(
                [
                    'message' => $ex->getMessage() ?? __('Error updating current vendor')
                ],
                500,
            );
        }
    }
}
