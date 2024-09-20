<?php

namespace App\Console\Commands;

use App\Models\AutoAssignment;
use App\Services\AutoAssignmentService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AutoCancelAutoAssign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:auto_assignment_cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make available pre-assigned order to driver';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        //
        $timeZone = setting('timeZone', 'UTC');
        $secondsFromNowAgo = Carbon::now($timeZone)->subSeconds(setting('alertDuration', 60) * 2)->format('Y-m-d h:i:s');
        $secondsFromNowAgoCarbon = Carbon::now($timeZone)->subSeconds(setting('alertDuration', 60) * 2);
        //logger("cancel auto-assignment",[$secondsFromNowAgoCarbon]);
        $autoAssignments = AutoAssignment::where('status', 'pending')->where('updated_at', '<', $secondsFromNowAgo)->limit(10)->get();
        if ($autoAssignments->isEmpty()) {
            //logger("2nd search");
            $autoAssignments = AutoAssignment::where('status', 'pending')
            ->whereTime('updated_at', '<', $secondsFromNowAgo)
            ->limit(10)
            ->get();

            if ($autoAssignments->isEmpty()) {
                // logger("3rd search");
                $autoAssignments = AutoAssignment::where('status', 'pending')
                    ->whereDate('updated_at', '<', $secondsFromNowAgo)
                    ->limit(10)
                    ->get();
            }
        }

        //loop through and delte the records while also sending notification to the driver
        foreach ($autoAssignments as $autoAssignment) {
            //carbon check the time diff
            if ($secondsFromNowAgoCarbon->lt($autoAssignment->updated_at)) {
                //logger("Auto-assign cancel ignore",[$autoAssignment->updated_at, $secondsFromNowAgoCarbon]);
                continue;
            }else{
                //logger("Auto-assign cancel",[$autoAssignment->updated_at, $secondsFromNowAgoCarbon]);
            }
            //driver 
            $driver = $autoAssignment->driver;
            //send the new order failture to driver via push notification
            $autoAssignmentSerivce = new AutoAssignmentService();
            $autoAssignmentSerivce->sendFailedNewOrderNotification($driver, $autoAssignment);
            $autoAssignmentSerivce->deleteNewOrderToFirebaseFirestore($driver);

            try {
                //reject the auto-assignment
                $autoAssignment->status = "rejected";
                $autoAssignment->save();
            } catch (\Exception $ex) {
                logger("error while rejecting order", [$ex]);
            }
        }



        //allow a resend of rejected auto-assignments
        $clearRejectedAutoAssignment = ((int)setting('clearRejectedAutoAssignment', 0)) ?? 0;

        if ($clearRejectedAutoAssignment > 0) {
            $minutesFromNowAgo = Carbon::now()->subMinutes($clearRejectedAutoAssignment)->format('Y-m-d h:i:s');
            $deletedAutoAssignments = AutoAssignment::whereDate('updated_at', '<', $minutesFromNowAgo)->limit(20)->delete();
        }
        return 0;
    }
}
