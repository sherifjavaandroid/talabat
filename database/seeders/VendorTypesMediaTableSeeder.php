<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VendorTypesMediaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        //assign icons
        //images stored in public/images/vendor/types
        $icons = [
            'parcel' => 'parcel.png',
            'food' => 'food.png',
            'grocery' => 'grocery.png',
            'pharmacy' => 'pharmacy.png',
            'service' => 'service.png',
            'taxi' => 'taxi.png',
            'booking' => 'booking.png',
            'commerce' => 'commerce.png',
        ];

        foreach ($icons as $slug => $icon) {
            if ($slug == "booking") {
                $vendorType = \App\Models\VendorType::where('slug', "service")
                    ->where("name", "Like", "%booking%")
                    ->first();
            } else {
                $vendorType = \App\Models\VendorType::where('slug', $slug)->first();
            }
            if (!empty($vendorType)) {
                //
                $vendorType->clearMediaCollection("logo");
                $photo = public_path('images/vendor/types/' . $icon);
                //keep the original image
                $vendorType->addMedia($photo)
                    ->preservingOriginal()
                    ->toMediaCollection("logo");
            }
        }
    }
}
