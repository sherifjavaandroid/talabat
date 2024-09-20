<?php

namespace App\Traits;

use App\Models\VehicleType;

trait VehicleTrait
{

    public $vehicleTypes;
    public $carModelSearchClause = [];
    public $car_make_id;
    public $car_model_id;
    public $driver_id;
    public $vehicle_type_id;
    public $reg_no;
    public $color;
    public $photos;

    public function updatedDriverType()
    {
        if (empty($this->vehicleTypes)) {
            $this->vehicleTypes = VehicleType::active()->get();
            $this->vehicle_type_id = $this->vehicleTypes->first()->id ?? null;
        }
    }

    //use to handle car make selected
    public function autocompleteCategorySelected($carMake)
    {
        try {
            $this->carModelSearchClause = ['car_make_id' => $carMake["id"]];
            $this->car_make_id = $carMake["id"];
            $this->emit('carModelQueryClasueUpdate', $this->carModelSearchClause);
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }

    //use to handle car model selected
    public function autocompleteAddressSelected($carModel)
    {
        try {
            $this->car_model_id = $carModel["id"];
        } catch (\Exception $ex) {
            logger("Error", [$ex]);
        }
    }
}
