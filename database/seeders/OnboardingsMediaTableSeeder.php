<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Onboarding;

class OnboardingsMediaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        $onboardings = Onboarding::all();
        //loop through onboardings and fetch image from unsplash

        foreach ($onboardings as $onboarding) {
            //image from public folder
            $photo = public_path('images/onboarding/' . $onboarding->id . '.png');
            if (!empty($photo)) {
                $onboarding->clearMediaCollection();
                $onboarding->addMedia($photo)
                    ->preservingOriginal()
                    ->toMediaCollection();
            }
        }
    }
}
