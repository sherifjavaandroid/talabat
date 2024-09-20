<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CancellationReason;
use Illuminate\Http\Request;

class CancellationReasonController extends Controller
{
    //
    public function index(Request $request)
    {
        $type = $request->type;
        return CancellationReason::when($type, function ($query, $type) {
            return $query->where('type', $type);
        })
            ->orWhere('type', 'both')
            ->get();
    }
}
