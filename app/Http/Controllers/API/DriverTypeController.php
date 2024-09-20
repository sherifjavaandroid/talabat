<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DriverType;
use App\Traits\FirebaseAuthTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleType;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Validator;




class DriverTypeController extends Controller
{
    //traits
    use FirebaseAuthTrait;

    //Switch from Taxi Driver to Regular Driver - vice versa
    public function switchType(Request $request)
    {
        $driver = User::find(Auth::id());
        if ($driver->assigned_orders > 0) {
            return response()->json([
                "message" => __("Driver type can not be switch while there are assigned orders. Please complete order before switching. Thank you")
            ], 400);
        }

        //no vehicle but try to switch to taxi driver
        if (empty($driver->vehicle) && $request->is_taxi) {
            return response()->json([
                "message" => __("You need to have a vehicle attached to account before you can switch. Please add vehicle to account")
            ], 400);
        }

        //set driver type
        DriverType::updateOrCreate(
            ["driver_id" => $driver->id],
            ["is_taxi" => $request->is_taxi],
        );

        $vehicleTypeId = $driver->vehicle->vehicle_type_id ?? 0;
        if (!$request->is_taxi) {
            $vehicleTypeId = 0;
        }

        $vehicleTypeId = (int) $vehicleTypeId;

        if ($vehicleTypeId == 0) {
            $vehicleTypeId = null;
        }

        //update driver firebase node
        //driver ref
        $driverRef = "drivers/" . $driver->id . "";
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

        //
        $driver->refresh();
        //
        return response()->json([
            "message" => __("Driver switch successful"),
            "data" => [
                "vehicle" => $driver->vehicle,
                "driver" => $driver,
            ]
        ], 200);
    }

    //
    public function registerVehicle(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'color' => 'required|nullable',
                'reg_no' => 'sometimes|nullable',
                'vehicle_type_id' => 'sometimes|nullable|exists:vehicle_types,id',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "message" => $this->readalbeError($validator),
            ], 400);
        }

        //
        try {


            DB::beginTransaction();

            $vehicle = new Vehicle();
            $vehicle->car_model_id = $request->car_model_id;
            $vehicle->driver_id = Auth::id();
            $vehicle->vehicle_type_id = $request->vehicle_type_id ?? VehicleType::active()->first()->id;
            $vehicle->reg_no = $request->reg_no;
            $vehicle->color = $request->color;
            $vehicle->is_active = false;
            $vehicle->save();


            if ($request->hasFile("documents")) {

                foreach ($request->documents as $document) {
                    $vehicle->addMedia($document->getRealPath())->toMediaCollection();
                }
            }


            DB::commit();
            //
            return response()->json([
                "message" => __("Vehcile Created Successfully. Your vehicle details will be reviewed and you will be notified via email/sms when approved. Thank you"),
                "data" => [
                    "vehicle" => $vehicle,
                    "driver" => User::find(Auth::id()),
                ]
            ]);
        } catch (Exception $error) {
            DB::rollback();
            return response()->json([
                "message" => $error->getMessage() ?? __("An error occurred please try again later"),
            ], 400);
        }
    }

    public function vehicles(Request $request)
    {
        return Vehicle::where('driver_id', Auth::id())->get();
    }

    public function activateVehicle(Request $request, $id)
    {

        $driver = User::find(Auth::id());
        if ($driver->assigned_orders > 0) {
            return response()->json([
                "message" => __("Driver type can not be switch while there are assigned orders. Please complete order before switching. Thank you")
            ], 400);
        }


        $vehicle = Vehicle::find($id);
        if (empty($vehicle) || $vehicle->driver_id != Auth::id() || !$vehicle->verified) {
            return response()->json([
                "message" => __("Selected vehicle can't be made active. Please contact support"),
            ], 401);
        }
        //
        $vehicle->is_active = true;
        $vehicle->save();
        return response()->json([
            "message" => __("Vehicle is activated successfully!"),
        ], 200);
    }
}
