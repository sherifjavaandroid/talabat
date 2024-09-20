<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\Referral;
use Asantibanez\LivewireCharts\Charts\LivewirePieChart;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

class ReferralReportLivewire extends BaseLivewireComponent
{

    //
    public $model = User::class;

    public function render()
    {

        return view('livewire.reports.referral_report', [
            "topReferringUserChart" => $this->topReferringUserChart(),
            "completedReferralChart" => $this->completedReferralChart(),
        ]);
    }


    public function topReferringUserChart()
    {
        //
        $dataSet = Referral::groupBy('user_id')->selectRaw('count(*) as total, user_id')->with('referringUser')->limit(10)->get();
        $chart = $dataSet->reduce(
            function ($pieChartModel, $data) {
                return $pieChartModel->addSlice($data["referringUser"]["name"] ?? "##Undefined##", $data["total"], $this->genColor());
            },
            LivewireCharts::pieChartModel()
                ->setTitle(__("Top Referral"))
                ->setAnimated(true)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(false)
        );
        return $chart;
    }

    public function completedReferralChart()
    {
        //
        $completedReferrals = Referral::where('confirmed')->count();
        $unCompletedReferrals = Referral::where('confirmed', 0)->count();
        $chart =  LivewireCharts::pieChartModel()
            ->setTitle(__("Confirmed Stats"))
            ->setAnimated(true)
            ->legendPositionBottom()
            ->legendHorizontallyAlignedCenter()
            ->setDataLabelsEnabled(false);

        $chart->addSlice(__("Confirmed"), $completedReferrals, $this->genColor());
        $chart->addSlice(__("UnConfirmed"), $unCompletedReferrals, $this->genColor());
        return $chart;
    }
}
