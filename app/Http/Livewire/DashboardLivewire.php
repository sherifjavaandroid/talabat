<?php

namespace App\Http\Livewire;

use App\Models\Earning;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Commission;
use App\Models\Product;
use App\Models\User;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Numbers\Number;
use Livewire\Component;


class DashboardLivewire extends Component
{
    public function render()
    {

        $user = User::find(\Auth::id());
        if ($user->hasRole('admin')) {
            if (\Schema::hasColumn("commissions", 'admin_commission')) {
                $totalEarnings = Number::n(Commission::sum('admin_commission'))->round(3)->getSuffixNotation();
            } else {
                $totalEarnings = Number::n(Order::mine()->currentStatus('delivered')->sum('total'))->round(3)->getSuffixNotation();
            }
        } else {

            if (\Schema::hasColumn("commissions", 'admin_commission')) {
                $earnedAmount = Commission::whereHas('order', function ($q) {
                    $q->mine();
                })->sum('vendor_commission');
            } else {
                $earning = Earning::firstOrCreate(
                    [
                        "vendor_id" => $user->vendor_id,
                    ],
                    [
                        "amount" => 0,
                    ]
                );

                $earnedAmount = $earning->amount;
            }
            $totalEarnings = Number::n($earnedAmount)->round(3)->getSuffixNotation();
        }

        $totalOrders = Number::n(Order::mine()->count())->round(3)->getSuffixNotation();
        $totalVendors = Number::n(Vendor::mine()->count())->round(3)->getSuffixNotation();
        $totalClients = Number::n(User::client()->count())->round(3)->getSuffixNotation();

        return view('livewire.dashboard', [
            "totalOrders" => $totalOrders,
            "totalEarnings" => $totalEarnings,
            "totalVendors" => $totalVendors,
            "totalClients" => $totalClients,

            "earningChart" => $this->earningChart(),
            "usersChart" => $this->usersChart(),
            "vendorsChart" => $this->vendorsChart(),
            "ordersChart" => $this->ordersChart(),
            "topSaleDaysChart" => $this->topOrderDaysChart(),
            "topSaleTimingChart" => $this->topSaleTimingChart(),
            "userRolesChart" => $this->userRolesChart(),
        ]);
    }




    public function earningChart()
    {

        //
        $chart = (new ColumnChartModel())->setTitle(__('Total Earning') . ' (' . Date("Y") . ')')->withoutLegend();
        $user = User::find(\Auth::id());

        for ($loop = 0; $loop < 12; $loop++) {
            $date = Carbon::now()->firstOfYear()->addMonths($loop);
            $formattedDate = $date->translatedFormat("M");
            if (empty($user->vendor_id)) {
                if (\Schema::hasColumn("commissions", 'admin_commission')) {
                    $data = Commission::whereMonth("created_at", $date)->whereYear('created_at', $date)->sum('admin_commission');
                } else {
                    $data = Order::mine()->whereMonth("created_at", $date)->whereYear('created_at', $date)->sum('total');
                }
            } else {
                if (\Schema::hasColumn("commissions", 'admin_commission')) {
                    $data = Commission::whereMonth("created_at", $date)
                        ->whereYear('created_at', $date)
                        ->whereHas('order', function ($q) {
                            $q->mine();
                        })
                        ->sum('vendor_commission');
                } else {
                    $data = Earning::where("vendor_id", $user->vendor_id)
                        ->whereMonth("created_at", $date)
                        ->whereYear('created_at', $date)
                        ->sum('amount');
                }
            }
            $data = number_format($data, 2, ".", ",");

            //
            $chart->addColumn(
                $formattedDate,
                $data,
                $this->genColor(),
            );
        }


        return $chart;
    }

    public function usersChart()
    {

        //
        $chart = (new ColumnChartModel())->setTitle(__('Users This Week'))->withoutLegend();

        for ($loop = 0; $loop < 7; $loop++) {
            $date = Carbon::now()->startOfWeek()->addDays($loop);
            $formattedDate = $date->translatedFormat("D");
            $data = User::whereDate("created_at", $date)->count();

            //
            $chart->addColumn(
                $formattedDate,
                $data,
                $this->genColor(),
            );
        }


        return $chart;
    }

    public function vendorsChart()
    {

        //
        $chart = (new ColumnChartModel())->setTitle(__('Vendors This Year'))->withoutLegend();

        for ($loop = 0; $loop < 12; $loop++) {
            $date = Carbon::now()->firstOfYear()->addMonths($loop);
            $formattedDate = $date->translatedFormat("M");
            $data = Vendor::whereMonth("created_at", $date)->whereYear('created_at', $date)->count();

            //
            $chart->addColumn(
                $formattedDate,
                $data,
                $this->genColor(),
            );
        }


        return $chart;
    }


    public function ordersChart()
    {

        //
        $chart = (new ColumnChartModel())->setTitle(__('Total Orders') . ' (' . Date("Y") . ')')->withoutLegend();

        for ($loop = 0; $loop < 12; $loop++) {
            $date = Carbon::now()->firstOfYear()->addMonths($loop);
            $formattedDate = $date->translatedFormat("M");
            $data = Order::mine()->whereMonth("created_at", $date)->whereYear('created_at', $date)->count();

            //
            $chart->addColumn(
                $formattedDate,
                $data,
                $this->genColor(),
            );
        }

        return $chart;
    }

    public function userRolesChart()
    {

        //
        $chart = (new ColumnChartModel())->setTitle(__('User Statistics') . ' (' . Date("Y") . ')')->setHorizontal(true)->withOutLegend();
        $roles = \Spatie\Permission\Models\Role::all();
        foreach ($roles as $role) {
            $data = User::role($role->name)->count();
            $chart->addColumn(
                $role->name,
                $data,
                $this->genColor(),
            );
        }




        return $chart;
    }


    public function topOrderDaysChart()
    {
        //
        $chart = (new ColumnChartModel())->setTitle(__('Total Ordering Days') . ' (' . Date("Y") . ')')->withOutLegend();
        $currentDate = Carbon::now();
        $firstDayOfWeek = $currentDate->startOfWeek();

        // Loop through each day of the week (assuming 1 is Sunday, 2 is Monday, ..., 7 is Saturday)
        for ($dayOfWeek = 1; $dayOfWeek <= 7; $dayOfWeek++) {
            // Fetch results for the current day
            $results = Order::mine()
                ->select(DB::raw('DAYOFWEEK(created_at) as day_of_week'), DB::raw('COUNT(*) as total'))
                ->where(DB::raw('DAYOFWEEK(created_at)'), $dayOfWeek)
                ->groupBy(DB::raw('DAYOFWEEK(created_at)'))
                ->first();
            // Store the results in the array
            //convert day of week to name of day
            $currentDayName = $firstDayOfWeek->format('l');
            $totalOrders = $results ? $results->total : 0;
            //
            $chart->addColumn(
                $currentDayName,
                $totalOrders,
                $this->genColor(),
            );
            // Move to the next day
            $firstDayOfWeek->addDay();
        }

        return $chart;
    }

    public function topSaleTimingChart()
    {
        //
        $chart = (new ColumnChartModel())->setTitle(__('Avg. Ordering Period') . ' (' . __("30days") . ')')->setHorizontal(true)->withOutLegend();
        $time = Carbon::createFromTime(0, 0, 0);
        $interval = 4;
        while ($time->lt(Carbon::createFromTime(24, 0, 0))) {
            // $timeArray[] = $time->format('H');
            $hourFormattedTime = $time->format('H');
            $results = Order::mine()
                ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as total'))
                ->where(DB::raw('HOUR(created_at)'),  $hourFormattedTime)
                ->groupBy(DB::raw('HOUR(created_at)'))
                //in last 30 days
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->first();
            $totalOrders = $results ? $results->total : 0;
            $labelTime =  $time->format('h:i A');
            $time->addHours($interval);
            $labelTime = $labelTime . " - " . $time->format('h:i A');
            $chart->addColumn(
                $labelTime,
                $totalOrders,
                $this->genColor(),
            );
        }

        // $currentDate = Carbon::now();
        // $firstDayOfWeek = $currentDate->startOfWeek();

        // // Loop through each day of the week (assuming 1 is Sunday, 2 is Monday, ..., 7 is Saturday)
        // for ($dayOfWeek = 1; $dayOfWeek <= 7; $dayOfWeek++) {
        //     // Fetch results for the current day
        //     $results = Order::mine()
        //         ->select(DB::raw('DAYOFWEEK(created_at) as day_of_week'), DB::raw('COUNT(*) as total'))
        //         ->where(DB::raw('DAYOFWEEK(created_at)'), $dayOfWeek)
        //         ->groupBy(DB::raw('DAYOFWEEK(created_at)'))
        //         ->first();
        //     // Store the results in the array
        //     //convert day of week to name of day
        //     $currentDayName = $firstDayOfWeek->format('l');
        //     $totalOrders = $results ? $results->total : 0;
        //     //
        //     $chart->addColumn(
        //         $currentDayName,
        //         $totalOrders,
        //         $this->genColor(),
        //     );
        //     // Move to the next day
        //     $firstDayOfWeek->addDay();
        // }

        return $chart;
    }



    public function genColor()
    {
        return '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
    }






    //
    public $topSellingVendors;
    public $topRatedVendors;
    public $topCustomers;
    public function fetchTopSellingVendors()
    {
        $this->topSellingVendors = Vendor::mine()->withCount('successful_sales')->orderBy('successful_sales_count', 'desc')->limit(6)->get();
    }

    public function fetchTopRatedVendors()
    {
        $this->topRatedVendors = Vendor::mine()
            ->withCount('ratings')->orderBy('ratings_count', 'desc')
            ->limit(6)->get();
    }

    public function fetchTopCustomers()
    {
        $this->topCustomers = User::withCount('orders', 'successful_orders')
            ->orderBy('successful_orders_count', 'desc')->limit(6)->get();
    }

    //
    public $myTopSellingProducts;
    public $topSellingProducts;
    public function fetchMyTopSellingProducts()
    {
        $this->myTopSellingProducts = Product::mine()
            ->withSum('successful_sales', 'quantity')
            ->withCount('successful_sales')->orderBy('successful_sales_count', 'desc')->limit(6)->get();
    }
    public function fetchTopSellingProducts()
    {
        $this->topSellingProducts = Product::mine()
            ->withSum('successful_sales', 'quantity')
            ->withCount('successful_sales')->orderBy('successful_sales_count', 'desc')->limit(6)->get();
    }

    public $userRolesSummary;
    public function fetchUserRoleSummary()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $this->userRolesSummary = [];
        foreach ($roles as $role) {
            $this->userRolesSummary[$role->name] = User::role($role->name)->count();
        }
    }
}
