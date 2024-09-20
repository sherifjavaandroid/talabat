<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\Order;
use App\Models\User;
use Asantibanez\LivewireCharts\Charts\LivewirePieChart;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

class CustomerReportLivewire extends BaseLivewireComponent
{

    //
    public $model = User::class;

    public function render()
    {

        return view('livewire.reports.customers_report', [
            "topCustomersChart" => $this->topCustomersChart(),
            "topSuccessfulOrdersChart" => $this->topSuccessfulOrdersChart(),
            "mostCancelsChart" => $this->mostCancelsChart(),
            "leastCustomersChart" => $this->leastCustomersChart(),
        ]);
    }


    public function topSuccessfulOrdersChart()
    {
        //
        $chart = (new LivewirePieChart());
        $dataSet = Order::currentStatus('delivered')->groupBy('user_id')->selectRaw('count(*) as total, user_id')->with('user')->limit(10)->get();
        $chart = $dataSet->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["user"]["name"] ?? "##Undefined##", ($data["total"] ?? 0), $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Top Buyers"))
                ->setAnimated(true)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }

    public function mostCancelsChart()
    {
        //
        $chart = (new LivewirePieChart());
        $dataSet = Order::currentStatus('cancelled')->groupBy('user_id')->selectRaw('count(*) as total, user_id')->with('user')->limit(10)->get();
        $chart = $dataSet->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["user"]["name"] ?? "##Undefined##", ($data["total"] ?? 0), $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Most Order Cancellation"))
                ->setAnimated(true)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }

    public function topCustomersChart()
    {
        //
        $chart = (new LivewirePieChart());
        $dataSet = Order::groupBy('user_id')->selectRaw('count(*) as total, user_id')->with('user')->limit(10)->get();
        $chart = $dataSet->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["user"]["name"] ?? "##Undefined##", ($data["total"] ?? 0), $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Most Orders"))
                ->setAnimated(true)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }

    public function leastCustomersChart()
    {
        //
        $chart = (new LivewirePieChart());
        $dataSet = Order::groupBy('user_id')->selectRaw('count(*) as total, user_id')->with('user')->orderBy('user_id', 'desc')->limit(10)->get();
        $chart = $dataSet->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["user"]["name"] ?? "##Undefined##", ($data["total"] ?? 0), $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Least Buyers"))
                ->setAnimated(true)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }
}
