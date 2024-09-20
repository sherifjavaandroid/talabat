<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Vendor;
use App\Models\User;
use App\Models\VehicleType;
use App\Models\Vehicle;
use App\Models\DriverType;
use App\Traits\ReferralTrait;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Propaganistas\LaravelPhone\PhoneNumber;

class PartnerController extends Controller
{

    use ReferralTrait;

    public function vendor(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'phone' => 'required|' . 'phone:' . setting('countryCode', "GH") . '|unique:users',
                'email' => 'required|email|unique:users',
                'name' => 'required',
                'password' => 'required',
                'vendor_email' => 'required|email|unique:vendors,email',
                'vendor_phone' => 'required|' . 'phone:' . setting('countryCode', "GH") . '|unique:vendors,phone',
                'vendor_name' => 'required',
                'vendor_type_id' => 'required|exists:vendor_types,id',
                'address' => 'required',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                "message" => $this->readalbeError($validator),
            ], 400);
        }

        //
        try {
            //
            $phone = new PhoneNumber($request->phone);
            $vendorPhone = new PhoneNumber($request->vendor_phone);
            //
            $user = User::where('phone', $phone)->first();
            if (!empty($user)) {
                throw new Exception(__("Account with phone already exists"), 1);
            }


            DB::beginTransaction();
            //
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $phone;
            $user->country_code = $phone->getCountry() ?? "";
            $user->commission = null;
            $user->password = Hash::make($request->password);
            $user->is_active = false;
            $user->save();
            //assign role
            $user->assignRole('manager');

            //create vendor
            $vendor = new Vendor();
            $vendor->name = $request->vendor_name;
            $vendor->email = $request->vendor_email;
            $vendor->phone = $vendorPhone;
            $vendor->is_active = false;
            $vendor->vendor_type_id = $request->vendor_type_id;
            $vendor->address = $request->address;
            $vendor->latitude = $request->latitude;
            $vendor->longitude = $request->longitude;
            $vendor->save();

            if ($request->hasFile("documents")) {

                foreach ($request->documents as $vendorDocument) {
                    $vendor->addMedia($vendorDocument->getRealPath())->toMediaCollection("documents");
                }
            }

            //assign manager to vendor
            $user->vendor_id = $vendor->id;
            $user->save();

            DB::commit();
            //
            return response()->json([
                "message" => __("Account Created Successfully. Your account will be reviewed and you will be notified via email/sms when account gets approved. Thank you"),
            ]);
        } catch (Exception $error) {
            DB::rollback();
            return response()->json([
                "message" => $error->getMessage() ?? __("An error occurred please try again later"),
            ], 400);
        }
    }

    public function driver(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'phone' => 'required|' . 'phone:' . setting('countryCode', "GH") . '|unique:users',
                'email' => 'required|email|unique:users',
                'name' => 'required',
                'password' => 'required',
                'color' => 'sometimes|nullable',
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

            //
            $phone = new PhoneNumber($request->phone);
            //
            $user = User::where('phone', $phone)->first();
            if (!empty($user)) {
                throw new Exception(__("Account with phone already exists"), 1);
            }


            DB::beginTransaction();
            //
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $phone;
            $user->country_code = $phone->getCountry() ?? "";
            $user->commission = null;
            $user->password = Hash::make($request->password);
            $user->is_active = false;
            $user->save();
            //assign role
            $user->assignRole('driver');

            //taxi section
            if ($request->driver_type == "taxi") {
                $vehicle = new Vehicle();
                $vehicle->car_model_id = $request->car_model_id;
                $vehicle->driver_id = $user->id;
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
            } else {

                if ($request->hasFile("documents")) {
                    $user->clearMediaCollection("documents");
                    foreach ($request->documents as $document) {
                        $user->addMedia($document->getRealPath())->toMediaCollection("documents");
                    }
                }
            }

            //create driver type
            DriverType::firstOrCreate(
                ["driver_id" => $user->id],
                ["is_taxi" => $request->driver_type == "taxi"],
            );

            //refer system is enabled
            $this->handlePartnerControllerReferral($request->referral_code, $user);

            DB::commit();
            //
            return response()->json([
                "message" => __("Account Created Successfully. Your account will be reviewed and you will be notified via email/sms when account gets approved. Thank you"),
            ]);
        } catch (Exception $error) {
            DB::rollback();
            return response()->json([
                "message" => $error->getMessage() ?? __("An error occurred please try again later"),
            ], 400);
        }
    }


    public function vehicleTypes()
    {
        return VehicleType::active()->get();
    }
    public function carMakes()
    {
        return CarMake::get();
    }
    public function carModels(Request $request)
    {
        return CarModel::when($request->car_make_id, function ($q) use ($request) {
            $q->where("car_make_id", $request->car_make_id);
        })->get();
    }
}
