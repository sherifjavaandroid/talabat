<?php

namespace App\Http\Livewire;

use Exception;
use LVR\Colour\Hex;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingsLivewire extends BaseLivewireComponent
{

    // App settings
    public $websiteHeaderTitle;
    public $websiteHeaderSubtitle;
    public $websiteHeaderImage;
    public $oldWebsiteHeaderImage;
    public $websiteFooterImage;
    public $websiteFooterBrief;
    public $oldWebsiteFooterImage;
    public $websiteIntroImage;
    public $oldWebsiteIntroImage;
    //social links
    public $fbLink;
    public $igLink;
    public $twLink;
    public $yuLink;

    //
    public $websiteFeatureTitle;
    public $websiteFeatureSubtitle;
    public $features = [];

    //
    public $websiteDriverJoinTitle;
    public $websiteDriverJoinDescription;
    public $websiteDriverJoinImage;

    public $websiteVendorJoinTitle;
    public $websiteVendorJoinDescription;
    public $websiteVendorJoinImage;

    public $websiteAboutUs;
    public $websiteAboutUsImage;
    public $websiteContactUs;
    public $websiteContactUsImage;

    public function loadContents()
    {
        //
        $this->websiteHeaderTitle = setting('websiteHeaderTitle', '');
        $this->websiteHeaderSubtitle = setting('websiteHeaderSubtitle', '');
        $this->websiteFooterBrief = setting('websiteFooterBrief', '');
        $this->websiteFeatureTitle = setting('websiteFeatureTitle', '');
        $this->websiteFeatureSubtitle = setting('websiteFeatureSubtitle', '');
        // features
        $features = setting('websiteFeatures', '[]');
        $this->features = json_decode($features, true);
        // images
        $this->oldWebsiteHeaderImage = setting('websiteHeaderImage', asset('images/website/intro.png'));
        $this->oldWebsiteFooterImage = setting('websiteFooterImage', asset('images/website/device-portrait.png'));
        $this->oldWebsiteIntroImage = setting('websiteIntroImage', asset('images/website/intro.png'));

        $this->fbLink = setting('social.fbLink', '');
        $this->igLink = setting('social.igLink', '');
        $this->twLink = setting('social.twLink', '');
        $this->yuLink = setting('social.yuLink', '');

        // join us section
        $this->websiteDriverJoinTitle = setting('websiteDriverJoinTitle', '');
        $this->websiteDriverJoinDescription = setting('websiteDriverJoinDescription', '');
        $this->websiteDriverJoinImage = setting('websiteDriverJoinImage', '');
        $this->websiteVendorJoinTitle = setting('websiteVendorJoinTitle', '');
        $this->websiteVendorJoinDescription = setting('websiteVendorJoinDescription', '');
        $this->websiteVendorJoinImage = setting('websiteVendorJoinImage', '');

        //contact & about us
        $this->websiteAboutUs = setting('websiteAboutUs');
        $this->websiteAboutUsImage = setting('websiteAboutUsImage');
        $this->websiteContactUs = setting('websiteContactUs');
        $this->websiteContactUsImage = setting('websiteContactUsImage');

        //
        $this->emit("loadSummerNote", "websiteDriverJoinDescription", $this->websiteDriverJoinDescription ?? "");
        $this->emit("loadSummerNote", "websiteVendorJoinDescription", $this->websiteVendorJoinDescription ?? "");
        $this->emit("loadSummerNote", "websiteAboutUs", $this->websiteAboutUs ?? "");
        $this->emit("loadSummerNote", "websiteContactUs", $this->websiteContactUs ?? "");
    }

    public function render()
    {
        return view('livewire.settings.website-settings');
    }


    public function addFeature()
    {
        $this->features[]  = [
            "title" => "",
            "description" => "",
            "image" => null,
        ];
    }

    public function deleteFeature($index)
    {
        unset($this->features[$index]);
        //reindex the array
        $this->features = array_values($this->features);
    }


    public function saveAppSettings()
    {

        // validate the feature
        $this->validate([
            "features.*.title" => "required|string",
            "features.*.description" => "required|string",
            "features.*.image" => "nullable|image|max:" . setting("filelimit.vendor_feature", 2048) . "",
        ], [
            "features.*.title.required" => "Title is required",
            "features.*.description.required" => "Description is required",
            "features.*.image.image" => "Must be an image file",
            "features.*.image.max" => "Maximum of " . setting("filelimit.vendor_feature", 2048) . "kb is allowed",
        ]);


        try {

            $this->isDemo();

            // store new logo
            if ($this->websiteHeaderImage) {
                $this->oldWebsiteHeaderImage = Storage::url($this->websiteHeaderImage->store('public/website'));
            }
            //store new footer
            if ($this->websiteFooterImage) {
                $this->oldWebsiteFooterImage = Storage::url($this->websiteFooterImage->store('public/website'));
            }
            //store new intro image
            if ($this->websiteIntroImage) {
                $this->oldWebsiteIntroImage = Storage::url($this->websiteIntroImage->store('public/website'));
            }
            //store new driver join us image image
            if ($this->websiteDriverJoinImage && is_file($this->websiteDriverJoinImage)) {
                $fileNewName = genFileName($this->websiteDriverJoinImage, 10);
                $this->websiteDriverJoinImage = Storage::url($this->websiteDriverJoinImage->storeAs('website', $fileNewName, 'public'));
            }
            //store new seller join us image image
            if ($this->websiteVendorJoinImage && is_file($this->websiteVendorJoinImage)) {
                $fileNewName = genFileName($this->websiteVendorJoinImage, 10);
                $this->websiteVendorJoinImage = Storage::url($this->websiteVendorJoinImage->storeAs('website', $fileNewName, 'public'));
            }

            //store about & contact us image
            if ($this->websiteAboutUsImage && is_file($this->websiteAboutUsImage)) {
                $fileNewName = genFileName($this->websiteAboutUsImage, 10);
                $this->websiteAboutUsImage = Storage::url($this->websiteAboutUsImage->storeAs('website', $fileNewName, 'public'));
            }
            if ($this->websiteContactUsImage && is_file($this->websiteContactUsImage)) {
                $fileNewName = genFileName($this->websiteContactUsImage, 10);
                $this->websiteContactUsImage = Storage::url($this->websiteContactUsImage->storeAs('website', $fileNewName, 'public'));
            }

            $websiteSettings = [
                'websiteHeaderTitle' =>  $this->websiteHeaderTitle,
                'websiteHeaderSubtitle' =>  $this->websiteHeaderSubtitle,
                // feature
                'websiteFeatureTitle' =>  $this->websiteFeatureTitle,
                'websiteFeatureSubtitle' =>  $this->websiteFeatureSubtitle,
                // images
                'websiteHeaderImage' =>  $this->oldWebsiteHeaderImage,
                'websiteFooterBrief' =>  $this->websiteFooterBrief,
                'websiteFooterImage' =>  $this->oldWebsiteFooterImage,
                'websiteIntroImage' =>  $this->oldWebsiteIntroImage,
                "social" => [
                    'fbLink' =>  $this->fbLink,
                    'igLink' =>  $this->igLink,
                    'twLink' =>  $this->twLink,
                    'yuLink' =>  $this->yuLink,
                ],
                // join us
                "websiteDriverJoinTitle" => $this->websiteDriverJoinTitle,
                "websiteDriverJoinDescription" => $this->websiteDriverJoinDescription,
                "websiteDriverJoinImage" => $this->websiteDriverJoinImage,
                "websiteVendorJoinTitle" => $this->websiteVendorJoinTitle,
                "websiteVendorJoinDescription" => $this->websiteVendorJoinDescription,
                "websiteVendorJoinImage" => $this->websiteVendorJoinImage,
                //info
                "websiteAboutUs" => $this->websiteAboutUs,
                "websiteAboutUsImage" => $this->websiteAboutUsImage,
                "websiteContactUs" => $this->websiteContactUs,
                "websiteContactUsImage" => $this->websiteContactUsImage,
            ];


            // update the site name
            setting($websiteSettings)->save();

            //if features not emtpy
            if (!empty($this->features ?? [])) {
                //prepare features
                $featuresData = [];
                foreach ($this->features as $feature) {
                    $selectedFile = $feature['image'];
                    if ($selectedFile) {
                        $fileNewName = genFileName($selectedFile, 12);
                        $fileSavedPath = $selectedFile->storeAs('website/features', $fileNewName, 'public');
                        $fileSavedPath = Storage::url($fileSavedPath);
                        //
                        $feature['image_url'] = $fileSavedPath;
                    }
                    $feature['image'] = null;
                    $featuresData[] = $feature;
                }
                //save features
                setting([
                    "websiteFeatures" => json_encode($featuresData)
                ])->save();
            }




            $this->showSuccessAlert(__("Website Settings saved successfully!"));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Website Settings save failed!"));
        }
    }
}