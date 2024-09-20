<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EarningReportResource;
use App\Http\Resources\SaleReportResource;
use App\Models\EarningReport;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\VendorManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorReportController extends Controller
{

    public function sales(Request $request)
    {
        $sDate = $request->start_date;
        $eDate = $request->end_date;
        $vendorId = Auth::user()->vendor_id;
        $vendorType = Auth::user()->vendor->vendor_type->slug;
        //
        if ($vendorType == "service" || $vendorType == "booking") {
            $query = OrderService::select('*', DB::raw('DATE(created_at) as date'), DB::raw('SUM(price) as total_amount'), DB::raw('SUM(hours) as total_unit'))
                ->whereHas("order", function ($q) use ($vendorId) {
                    $q->where('vendor_id', $vendorId)->currentStatus('delivered');
                })
                ->groupBy('service_id', DB::raw('DATE(created_at)'));
        } else  if ($vendorType == "parcel" || $vendorType == "package") {
            $query = Order::select('*', DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total_amount'), DB::raw('COUNT(*) as total_unit'))
                ->currentStatus('delivered')
                ->where('vendor_id', $vendorId)
                ->groupBy('package_type_id', DB::raw('DATE(created_at)'));
        } else {
            $query = OrderProduct::select('*', DB::raw('DATE(created_at) as date'), DB::raw('SUM(price) as total_amount'), DB::raw('SUM(quantity) as total_unit'))
                ->whereHas("order", function ($q) use ($vendorId) {
                    $q->where('vendor_id', $vendorId)->currentStatus('delivered');
                })
                ->groupBy('product_id', DB::raw('DATE(created_at)'));
        }

        //add the filters
        $salesReport = $query->when($sDate, function ($query) use ($sDate) {
            return $query->whereDate('created_at', ">=", $sDate);
        })->when($eDate, function ($query) use ($eDate) {
            return $query->whereDate('created_at', "<=", $eDate);
        })->paginate();

        //
        $salesReport = SaleReportResource::collection($salesReport);
        return response()->json($salesReport);
    }

    public function earnings(Request $request)
    {
        $sDate = $request->start_date;
        $eDate = $request->end_date;
        $vendorId = Auth::user()->vendor_id;
        $earningReports = EarningReport::whereHas("order", function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
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