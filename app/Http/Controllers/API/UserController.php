<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $role = $request->role;
        $authUser = User::find(Auth::id());
        if ($authUser->role("manager") && $role != "driver") {
            return response()->json([
                "message" => __("Not Authorised for the request"),
            ], 401);
        }

        if (!$authUser->hasAnyRole("manager", "admin")) {
            return response()->json([
                "message" => __("Not Authorised for the request"),
            ], 401);
        }

        $users = User::with('roles')->when($role, function ($query) use ($role) {
            return $query->whereHas('roles', function ($query) use ($role) {
                return $query->where('name', $role);
            });
        })->when($role == "driver", function ($query) use ($request) {
            return $query->whereNull('vendor_id')
                ->when($request->vehicle_type_id, function ($query) use ($request) {
                    return $query->whereHas('vehicle', function ($q) use ($request) {
                        return $q->where('vehicle_type_id', $request->vehicle_type_id);
                    });
                }, function ($query) use ($request) {
                    // return $query->whereDoesntHave('vehicle');
                    //return user with driver_type is_taxi = 0
                    // return $query->whereHas('driver_type', function ($q) {
                    //     return $q->where('is_taxi', 0);
                    // });
                    return $query->whereDoesntHave('vehicle')
                        ->orWhereHas('driver_type', function ($q) {
                            return $q->where('is_taxi', 0);
                        });
                });
        })->get();

        //
        if (!empty(Auth::user()->vendor_id) && $role == "driver") {
            $personalDrivers = User::role('driver')->where('vendor_id', Auth::user()->vendor_id)->get();
            if (!empty($personalDrivers) && count($personalDrivers) > 0) {
                $users = $personalDrivers;
            }
        }

        return response()->json([
            "data" => $users,
        ], 200);
    }

    public function myProfile(Request $request)
    {
        return User::find(Auth::id());
    }
}