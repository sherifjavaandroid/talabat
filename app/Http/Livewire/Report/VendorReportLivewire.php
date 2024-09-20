<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\Vendor;
use Asantibanez\LivewireCharts\Charts\LivewirePieChart;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

class VendorReportLivewire extends BaseLivewireComponent
{

    //
    public $model = Vendor::class;

    public function render()
    {

        return view('livewire.reports.vendors_report', [
            "topVendorsChart" => $this->topVendorsChart(),
            "topEarningVendorsChart" => $this->topEarningVendorsChart(),
            "leastVendorsChart" => $this->leastVendorsChart(),
        ]);
    }


    public function topVendorsChart()
    {
        //
        $chart = (new LivewirePieChart());
        $dataSet = $this->model::orderByPowerJoinsCount('sales.id', 'desc')->limit(10)->get();
        $chart = $dataSet->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["name"] ?? "##Undefined##", $data["sales_aggregation"], $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Top Vendors(Sales)"))
                ->setAnimated(true)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }

    public function topEarningVendorsChart()
    {
        //
        $chart = (new LivewirePieChart());
        $dataSet = $this->model::orderByPowerJoinsSum('earning.amount', 'desc')->limit(10)->get();
        $chart = $dataSet->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["name"] ?? "##Undefined##", $data["earning_aggregation"], $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Top Earning Vendors"))
                ->setAnimated(true)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }

    public function leastVendorsChart()
    {
        //
        $chart = (new LivewirePieChart());
        $dataSet = $this->model::orderByPowerJoinsCount('sales.id', 'asc')->limit(10)->get();
        $chart = $dataSet->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["name"] ?? "##Undefined##", $data["sales_aggregation"], $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Least Vendors"))
                ->setAnimated(true)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }
}
