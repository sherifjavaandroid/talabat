<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\Category;
use App\Models\Service;
use Asantibanez\LivewireCharts\Charts\LivewirePieChart;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

class ServiceReportLivewire extends BaseLivewireComponent
{

    //
    public $model = Service::class;

    public function render()
    {

        return view('livewire.reports.services_report', [
            "servicesChart" => $this->servicesChart(),
            "topCategoriesChart" => $this->categoriesChart(),
        ]);
    }


    public function servicesChart()
    {
        //
        $chart = (new LivewirePieChart());
        $dataSet = $this->model::mine()->orderByPowerJoinsCount('sales.id', 'desc')->limit(10)->get();
        $chart = $dataSet->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["name"], $data["sales_aggregation"], $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Top Services"))
                ->setAnimated(true)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }

    public function categoriesChart()
    {
        //
        $chart = (new LivewirePieChart());
        $dataSet = Category::whereHas('services', function ($query) {
            return $query->mine();
        })->orderByPowerJoinsCount('services.sales.id', 'desc')->limit(10)->get();
        $chart = $dataSet->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["name"], $data["sales_aggregation"], $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Top Categories"))
                ->setAnimated(true)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }
}
