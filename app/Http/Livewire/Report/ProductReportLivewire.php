<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\Category;
use App\Models\Product;
use Asantibanez\LivewireCharts\Charts\LivewirePieChart;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductReportLivewire extends BaseLivewireComponent
{

    //
    public $model = Product::class;

    public function render()
    {

        return view('livewire.reports.products_report', [
            "topProductsChart" => $this->productsChart(),
            "topCategoriesChart" => $this->categoriesChart(),
        ]);
    }


    public function productsChart()
    {
        //
        $user = User::find(Auth::id());
        //
        $chart = (new LivewirePieChart());
        $dataSet = $this->model::mine()->orderByPowerJoinsCount('sales.id', 'desc')->limit(10)->get();
        $chart = $dataSet->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["name"], $data["sales_aggregation"], $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Top Products"))
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
        $dataSet = Category::whereHas('products',function($query){
            return $query->mine();
        })->orderByPowerJoinsCount('products.sales.id', 'desc')->limit(10)->get();
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
