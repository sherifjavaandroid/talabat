<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Onboarding;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{

    public function index(Request $request)
    {

        $onboarding = Onboarding::when($request->type, function ($q) use ($request) {
            return $q->orWhere('type', $request->type);
        })->active()->get();
        return response()->json($onboarding, 200);
    }
}
