<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{

    public function index(Request $request)
    {
        $onboarding = Faq::whereNull('type')->when($request->type, function ($q) use ($request) {
            return $q->orWhere('type', $request->type);
        })->active()->get();
        return response()->json($onboarding, 200);
    }
}
