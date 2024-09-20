<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Traits\FirebaseAuthTrait;
use App\Models\Vehicle;
use App\Models\DriverType;

class DriverVehicleTypeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use FirebaseAuthTrait;
    public $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        //get any connected vehicle to the driver
        $vehicleTypeId = 0;
        //get driver type
        $driverType = DriverType::where('driver_id', $this->user->id)->first();
        if ($driverType != null && !$driverType->is_taxi) {
            $vehicleTypeId = null;
        } else {
            $vehicle = Vehicle::available()->where('driver_id', $this->user->id)->first();
            if (!empty($vehicle)) {
                $vehicleTypeId = (int) $vehicle->vehicle_type_id;
            }
        }

        //set $vehicleTypeId to null if it is 0
        if ($vehicleTypeId == 0 || $vehicleTypeId == "0") {
            $vehicleTypeId = null;
        }
        //sync vehicle type id
        //driver ref
        $driverRef = "drivers/" . $this->user->id . "";
        $firestoreClient = $this->getFirebaseStoreClient();
        //
        try {
            $firestoreClient->addDocument(
                $driverRef,
                [
                    'vehicle_type_id' => $vehicleTypeId
                ]
            );
        } catch (\Exception $error) {
            try {
                $firestoreClient->updateDocument(
                    $driverRef,
                    [
                        'vehicle_type_id' => $vehicleTypeId
                    ]
                );
            } catch (\Exception $error) {
                logger("Dirver DATA update error", [$error]);
            }
        }
    }
}
