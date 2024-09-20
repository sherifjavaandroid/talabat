<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\Commission;
use App\Models\Order;

class SummaryReportLivewire extends BaseLivewireComponent
{

    public $startDate;
    public $endDate;
    //
    public $adminEarned = 0;
    public $vendorsTotalEarned = 0;
    public $driversTotalEarned = 0;
    public $totalSales = 0;

    //stats
    public $progressOrder = 0;
    public $completedOrder = 0;
    public $failedOrder = 0;
    public $cancelledOrder = 0;

    public function mount()
    {
        $this->startDate = now()->subDays(14)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.reports.summary_report');
    }


    public function loadData()
    {

        //
        if (!\Schema::hasColumn("commissions", 'admin_commission')) {
            logger("raw data");
            $this->adminEarned = $adminVendorCommission = $this->dateQuery(Commission::query())->sum('vendor_commission');
            $this->adminEarned += $adminDriverCommission = $this->dateQuery(Commission::query())->sum('driver_commission');

            //
            $this->vendorsTotalEarned = $this->dateQuery(Order::currentStatus(['completed', 'delivered']))->sum('sub_total');
            $this->vendorsTotalEarned -= $adminVendorCommission;
            //
            $this->driversTotalEarned = $this->dateQuery(Order::currentStatus(['completed', 'delivered']))->sum('delivery_fee');
            $this->driversTotalEarned -= $adminDriverCommission;
        } else {
            $this->adminEarned = $this->dateQuery(Commission::query())->sum('admin_commission');
            $this->vendorsTotalEarned = $this->dateQuery(Commission::query())->sum('vendor_commission');
            $this->driversTotalEarned = $this->dateQuery(Commission::query())->sum('driver_commission');
        }
        //
        $this->totalSales = $this->dateQuery(Order::currentStatus(['completed', 'delivered']))->sum('total');



        //stats
        $this->progressOrder = $this->dateQuery(Order::otherCurrentStatus(['completed', 'delivered', "failed", "cancelled"]))->count();
        $this->completedOrder = $this->dateQuery(Order::currentStatus(['completed', 'delivered']))->count();
        $this->failedOrder = $this->dateQuery(Order::currentStatus(["failed"]))->count();
        $this->cancelledOrder = $this->dateQuery(Order::currentStatus(["cancelled"]))->count();
    }


    public function dateQuery($query)
    {
        $query = $query->when($this->startDate, function ($query) {
            return $query->whereDate("created_at", ">=", $this->startDate);
        })
            ->when($this->endDate, function ($query) {
                return $query->whereDate("created_at", "<=", $this->endDate);
            });

        return $query;
    }
}
