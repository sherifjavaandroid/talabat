<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EarningReportResource;
use App\Http\Resources\PayoutReportResource;
use App\Models\EarningReport;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverReportController extends Controller
{

    public function payouts(Request $request)
    {
        $sDate = $request->start_date;
        $eDate = $request->end_date;
        $userId = Auth::id();
        //add the filters
        // $payoutsReport = Payout::when($sDate, function ($query) use ($sDate) {
        $payoutsReport = Payout::with('payment_account')->where('user_id', $userId)
            ->when($sDate, function ($query) use ($sDate) {
                return $query->whereDate('created_at', ">=", $sDate);
            })->when($eDate, function ($query) use ($eDate) {
                return $query->whereDate('created_at', "<=", $eDate);
            })->paginate();
        //
        $payoutsReport = PayoutReportResource::collection($payoutsReport);
        return response()->json($payoutsReport);
    }

    public function earnings(Request $request)
    {
        $sDate = $request->start_date;
        $eDate = $request->end_date;
        $userId = Auth::id();
        $earningReports = EarningReport::whereHas("order", function ($q) use ($userId) {
            $q->where('driver_id', $userId);
        })->when($sDate, function ($query) use ($sDate) {
            return $query->whereDate('created_at', ">=", $sDate);
        })->when($eDate, function ($query) use ($eDate) {
            return $query->whereDate('created_at', "<=", $eDate);
        })
            ->select(
                '*',
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(earning) as total_earning'),
                DB::raw('SUM(commission) as total_commission'),
                DB::raw('SUM(balance) as total_balance')
            )
            // ->groupBy('created_at')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->paginate();
        //
        $earningReports = EarningReportResource::collection($earningReports);
        return response()->json($earningReports);
    }
}