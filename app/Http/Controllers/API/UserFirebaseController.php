<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFirebaseController extends Controller
{
    //
    public function syncTokens(Request $request)
    {
        try {
            $user = User::find(Auth::id());
            $user->syncFCMTokens($request->tokens ?? null);
            return response()->json([
                "message" => __("Token synced successfully"),
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                "message" => $ex->getMessage() ?? __("Token sync failed"),
            ], 400);
        }
    }
}
