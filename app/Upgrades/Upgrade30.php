<?php

namespace App\Upgrades;

use App\Models\DeliveryZone;
use App\Models\Service;
use App\Models\Vendor;
use App\Models\VendorDeliveryZone;
use App\Models\VendorType;
use App\Traits\GoogleMapApiTrait;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;


class Upgrade30 extends BaseUpgrade
{
    use GoogleMapApiTrait;

    public $versionName = "1.4.6";
    //Runs or migrations to be done on this version
    public function run()
    {

        //
        if (Schema::hasColumn('services', 'per_hour')) {
            //get all the per hour service and pluck the ids
            $perHourServiceIds = Service::where('per_hour', 1)->get()->pluck("id");
            //drop the per_hour column
            if (Schema::hasColumn('services', 'per_hour')) {
                Schema::table('services', function (Blueprint $table) {
                    $table->dropColumn('per_hour');
                });
            }
            //add the duration column
            if (!Schema::hasColumn('services', 'duration')) {
                Schema::table('services', function (Blueprint $table) {
                    $table->enum('duration', ['fixed', 'hour', 'day', 'month', 'year'])->default('fixed');
                });
            }

            //set back the per_hour services
            Service::whereIn('id', $perHourServiceIds)->update(
                [
                    "duration" => "hour"
                ]
            );
        }

        //delivery zone pivot
        if (!Schema::hasTable('delivery_zone_vendor')) {
            Artisan::call('migrate --path=database/migrations/2022_01_11_082033_create_delivery_zone_vendor_pivot_table.php --force');
        }

        //migrate the vendor delivery zone
        if (Schema::hasColumn('vendors', 'delivery_zone_id')) {
            //get all the vendors
            $vendors = Vendor::whereNotNull('delivery_zone_id')->get();
            foreach ($vendors as $vendor) {
                VendorDeliveryZone::create([
                    "vendor_id" => $vendor->id,
                    "delivery_zone_id" => $vendor->delivery_zone_id,
                ]);
            }


            //drop the delivery_zone_id column
            if (Schema::hasColumn('vendors', 'delivery_zone_id')) {
                \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                Schema::table('vendors', function (Blueprint $table) {
                    $table->dropForeign(['delivery_zone_id']);
                });
                \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        }


        //add latitude column
        if (!Schema::hasColumn('delivery_zones', 'latitude')) {
            Schema::table('delivery_zones', function (Blueprint $table) {
                $table->string('latitude')->nullable()->after('name');
                $table->string('longitude')->nullable()->after('name');
                $table->double('radius')->nullable()->after('name');
            });

            //migrate and get center lat/lng and radius
            $deliveryZones = DeliveryZone::get();
            try {
                foreach ($deliveryZones as $deliveryZone) {
                    //
                    $coordinateCollection = $deliveryZone->points->toArray();
                    //
                    if (count($coordinateCollection) > 1) {
                        $centerCoordinate = $this->getCenter($coordinateCollection);
                        $centerCoordinateDistance = $this->getLinearDistance(
                            "" . $centerCoordinate[0] . "," . $centerCoordinate[1] . "",
                            "" . $coordinateCollection[0]['lat'] . "," . $coordinateCollection[0]['lng'] . "",
                        );
                        $deliveryZone->latitude = $centerCoordinate[0];
                        $deliveryZone->longitude = $centerCoordinate[1];
                        $deliveryZone->radius = $centerCoordinateDistance;
                    } else {
                        $deliveryZone->latitude = $coordinateCollection[0]["lat"];
                        $deliveryZone->longitude = $coordinateCollection[0]["lng"];
                        $deliveryZone->radius = 0;
                    }
                    $deliveryZone->save();
                }
            } catch (\Exception $ex) {
                logger("ex", [$ex]);
            }
        }

        //add new vendor types
        VendorType::firstOrNew([
            "name" => "Booking"
        ], [
            "name" => "Booking",
            "color" => "#ff0034",
            "description" => "Hotel/Housing/Rental Booking",
            "slug" => "service",
            "is_active" => 1
        ]);

        //add color to category model 
        if (!Schema::hasColumn('categories', 'color')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('color')->default('#eeeeee');
            });
        }
    }


}
