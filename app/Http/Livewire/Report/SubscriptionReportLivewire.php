<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\Subscription;
use App\Models\SubscriptionVendor;
use Asantibanez\LivewireCharts\Charts\LivewirePieChart;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

class SubscriptionReportLivewire extends BaseLivewireComponent
{

    //
    public $model = Subscription::class;

    public function render()
    {

        return view('livewire.reports.subscriptions_report',[
            "topVendorsChart" => $this->topVendorsChart(),
            "topSubscriptionVendorsChart" => $this->topSubscriptionVendorsChart(),
            "leastSubscriptionVendorsChart" => $this->leastVendorsChart(),
        ]);
    }


    public function topVendorsChart()
    {
        //
        $chart = (new LivewirePieChart());
        $dataSet = SubscriptionVendor::orderByPowerJoinsCount('vendor.id', 'desc')->limit(10)->get();
        $chart = $dataSet->reduce(function ($pieChartModel, $data) {
            return $pieChartModel->addSlice($data["vendor"]["name"]?? "##Undefined##", $data["vendor_aggregation"],$this->genColor());
        }, LivewireCharts::pieChartModel()
            ->setTitle(__("Top Vendor by no of Subscriptions"))
            ->setAnimated(true)
            ->legendPositionBottom()
            ->legendHorizontallyAlignedCenter()
            ->setDataLabelsEnabled(false)
        );        
        return $chart;
    }

    public function topSubscriptionVendorsChart()
    {
         //
         $chart = (new LivewirePieChart());
         $dataSet = SubscriptionVendor::orderByPowerJoinsCount('subscription.id', 'desc')->limit(10)->get();
         $chart = $dataSet->reduce(function ($pieChartModel, $data) {
             return $pieChartModel->addSlice($data["subscription"]["name"]?? "##Undefined##", $data["subscription_aggregation"],$this->genColor());
         }, LivewireCharts::pieChartModel()
             ->setTitle(__("Top Subscription"))
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
          $dataSet = SubscriptionVendor::orderByPowerJoinsCount('subscription.id', 'asc')->limit(10)->get();
          $chart = $dataSet->reduce(function ($pieChartModel, $data) {
              return $pieChartModel->addSlice($data["subscription"]["name"]?? "##Undefined##", $data["subscription_aggregation"],$this->genColor());
          }, LivewireCharts::pieChartModel()
              ->setTitle(__("Least Subscription"))
              ->setAnimated(true)
              ->legendPositionBottom()
              ->legendHorizontallyAlignedCenter()
              ->setDataLabelsEnabled(false)
          );        
          return $chart;
    }



    

}
