<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\CouponUser;
use Asantibanez\LivewireCharts\Charts\LivewirePieChart;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

class CouponReportLivewire extends BaseLivewireComponent
{

    //
    public $model = CouponUser::class;

    public function render()
    {

        return view('livewire.reports.coupons_report', [
            "topCouponsChart" => $this->couponChart(),
            "topUsersChart" => $this->userChart(),
        ]);
    }


    public function couponChart()
    {
        //
        $chart = (new LivewirePieChart());
        $coupons = CouponUser::groupBy('coupon_id')->selectRaw('count(*) as total, coupon_id')->with('coupon')->limit(5)->get();
        $chart = $coupons->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["coupon"]["code"] ?? "##Undefined##", $data["total"], $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Total Coupons"))
                ->setAnimated(true)
                //->withoutLegend()
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }

    public function userChart()
    {
        //
        $chart = (new LivewirePieChart());
        $users = CouponUser::groupBy('user_id')->selectRaw('count(*) as total, user_id')->with('user')->limit(5)->get();
        $chart = $users->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["user"]["name"] ?? "##Undefined##", $data["total"], $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Top Coupon Users"))
                ->setAnimated(true)
                //->withoutLegend()
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }

    public function genColor()
    {
        return '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
    }
}
