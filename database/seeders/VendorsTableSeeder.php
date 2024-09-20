<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\VendorType;
use Illuminate\Database\Seeder;

class VendorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('vendors')->delete();

        $vendorTypeIds = VendorType::get()->pluck('id')->toArray();
        $faker = \Faker\Factory::create();
        $totalVendors = rand(3, 9);
        for ($i = 0; $i < $totalVendors; $i++) {
            $model = new Vendor();
            $model->name = $faker->company;
            $model->description = $faker->catchPhrase;
            $model->delivery_fee = $faker->randomNumber(2, false);
            $model->delivery_range = $faker->randomNumber(3, false);
            $model->tax = $faker->randomNumber(2, false);
            $model->phone = $faker->phoneNumber;
            $model->email = $faker->email;
            $model->address = $faker->address;
            $model->latitude = $faker->latitude();
            $model->longitude = $faker->longitude();
            $model->tax = rand(0, 1);
            $model->pickup = rand(0, 1);
            $model->delivery = rand(0, 1);
            $model->is_active = 1;
            $model->vendor_type_id = $vendorTypeIds[array_rand($vendorTypeIds)];
            $model->save();

            //
            try {
                $model->addMediaFromUrl("https://source.unsplash.com/800x480/?logo")->toMediaCollection("logo");
                $model->addMediaFromUrl("https://source.unsplash.com/1280x720/?vendor")->toMediaCollection("feature_image");
            } catch (\Exception $ex) {
                logger("Error", [$ex->getMessage()]);
            }
        }
    }
}
