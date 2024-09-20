<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Review;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{


    public function store(Request $request)
    {

        try {

            //add comment column in ratings table, if doesn't exist
            if (!\Schema::hasColumn('ratings', 'comment')) {
                \Schema::table('ratings', function ($table) {
                    $table->text('comment')->nullable();
                });
            }


            //
            $vendorId = $request->vendor_id;
            $driverId = $request->driver_id;
            $userId = $request->user_id;

            if (!empty($vendorId)) {
                $review = Review::where('user_id', Auth::id())
                    ->where([
                        'vendor_id' => $request->vendor_id,
                        'order_id' => $request->order_id
                    ])->first();
                if (!empty($review)) {
                    throw new \Exception(__("Vendor already rated"), 1);
                }

                $model = new Review();
                $model->user_id = Auth::id();
                $model->vendor_id = $request->vendor_id;
                $model->order_id = $request->order_id;
                $model->rating = $request->rating;
                $model->review = $request->review;
                $model->save();

                //
                $vendor = Vendor::find($request->vendor_id);
                $vendor->rate($request->rating);


                return response()->json([
                    "message" => __("Vendor rated successfully")
                ], 200);
            } else if (!empty($userId)) {

                // $review = Review::where('user_id',  $request->user_id)
                //     ->where([
                //         'driver_id' => Auth::id(),
                //         'order_id' => $request->order_id
                //     ])->first();
                // if (!empty($review)) {
                //     throw new \Exception(__("Rider already rated"), 1);
                // }

                // $model = new Review();
                // $model->user_id = $request->user_id;
                // $model->driver_id = Auth::id();
                // $model->order_id = $request->order_id;
                // $model->rating = $request->rating;
                // $model->review = $request->review;
                // $model->save();

                //
                $user = User::find($request->user_id);
                $user->rate($request->rating);


                return response()->json([
                    "message" => __("Rider rated successfully")
                ], 200);
            } else if (!empty($driverId)) {

                $review = Review::where('user_id',  Auth::id())
                    ->where([
                        'driver_id' => $driverId,
                        'order_id' => $request->order_id
                    ])->first();
                if (!empty($review)) {
                    throw new \Exception(__("Driver already rated"), 1);
                }

                $model = new Review();
                $model->user_id = Auth::id();
                $model->driver_id = $driverId;
                $model->order_id = $request->order_id;
                $model->rating = $request->rating;
                $model->review = $request->review;
                $model->save();

                //
                $user = User::find($driverId);
                $user->rate($request->rating);


                return response()->json([
                    "message" => __("Driver rated successfully")
                ], 200);
            } else {
                $review = Review::where('user_id', Auth::id())
                    ->where([
                        'driver_id' => $request->driver_id,
                        'order_id' => $request->order_id
                    ])->first();
                if (!empty($review)) {
                    throw new \Exception(__("Already rated"), 1);
                }

                $model = new Review();
                $model->user_id = Auth::id();
                $model->driver_id = $request->driver_id;
                $model->order_id = $request->order_id;
                $model->rating = $request->rating;
                $model->review = $request->review;
                $model->save();

                //
                $driver = User::find($request->driver_id);
                $driver->rate($request->rating);


                return response()->json([
                    "message" => __("Rated successfully")
                ], 200);
            }
        } catch (\Exception $ex) {

            return response()->json([
                "message" =>  $ex->getMessage() ?? __("Rating failed")
            ], 400);
        }
    }
}
