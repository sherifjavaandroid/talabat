<?php

namespace App\Observers;

use App\Models\Vehicle;
use App\Services\JobHandlerService;
use App\Traits\FirebaseAuthTrait;

class VehicleObserver
{

    use FirebaseAuthTrait;

    public function created(Vehicle $vehicle)
    {
        $driver = $vehicle->driver;
        $this->deactivateOtherVehicles($vehicle);
        //
        (new JobHandlerService())->driverVehicleTypeJob($driver);
    }

    public function updated(Vehicle $vehicle)
    {
        $driver = $vehicle->driver;
        $this->deactivateOtherVehicles($vehicle);
        //
        (new JobHandlerService())->driverVehicleTypeJob($driver);
    }


    //this will help prevent having more than one active vehicle for driver
    public function deactivateOtherVehicles($vehicle)
    {
        \DB::statement("UPDATE vehicles SET is_active = 0 where driver_id = " . $vehicle->driver_id . " AND id != " . $vehicle->id . "");
    }
}
