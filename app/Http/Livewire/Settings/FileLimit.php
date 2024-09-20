<?php

namespace App\Http\Livewire\Settings;


class FileLimit extends BaseSettingsComponent
{

    //
    public $banner_limit;
    public $vendor_type_limit;
    public $package_type_limit;
    public $vendor_logo_limit;
    public $vendor_feature_limit;
    public $category_limit;
    public $sub_category_limit;
    public $max_product_images_limit;
    public $product_image_size_limit;
    public $max_service_images_limit;
    public $service_image_size_limit;
    public $max_product_digital_files_size;
    //prescription file limit
    public $prescription_file_limit;
    public $prescription_file_size_limit;


    public function mount()
    {
        $this->prevData();
    }


    public function render()
    {
        return view('livewire.settings.file_limits');
    }



    //
    public function prevData()
    {
        $this->banner_limit = setting('filelimit.banner', 300);
        $this->vendor_type_limit = setting('filelimit.vendor_type', 400);
        $this->package_type_limit = setting('filelimit.package_type', 400);
        $this->vendor_logo_limit = setting('filelimit.vendor_logo', 300);
        $this->vendor_feature_limit = setting('filelimit.vendor_feature', 500);
        $this->category_limit = setting('filelimit.category', 300);
        $this->sub_category_limit = setting('filelimit.sub_category', 300);
        $this->max_product_images_limit = setting('filelimit.max_product_images', 2);
        $this->product_image_size_limit = setting('filelimit.product_image_size', 300);
        $this->max_service_images_limit = setting('filelimit.max_service_images', 2);
        $this->service_image_size_limit = setting('filelimit.service_image_size', 300);
        $this->max_product_digital_files_size = setting('filelimit.max_product_digital_files_size', 2);
        //prescription file limit
        $this->prescription_file_limit = setting('filelimit.prescription.file_limit', 2);
        $this->prescription_file_size_limit = setting('filelimit.prescription.file_size_limit', 300);
    }

    public function saveFileLimits()
    {

        try {

            $this->isDemo();
            //save settings
            setting([
                "filelimit" => [
                    "banner" => $this->banner_limit,
                    "vendor_type" => $this->vendor_type_limit,
                    "package_type" => $this->package_type_limit,
                    "vendor_logo" => $this->vendor_logo_limit,
                    "vendor_feature" => $this->vendor_feature_limit,
                    "category" => $this->category_limit,
                    "sub_category" => $this->sub_category_limit,
                    "max_product_images" => $this->max_product_images_limit,
                    "product_image_size" => $this->product_image_size_limit,
                    "max_service_images" => $this->max_service_images_limit,
                    "service_image_size" => $this->service_image_size_limit,
                    "max_product_digital_files_size" => $this->max_product_digital_files_size,
                    //prescription file limit
                    "prescription.file_limit" => $this->prescription_file_limit,
                    "prescription.file_size_limit" => $this->prescription_file_size_limit,
                ]
            ])->save();

            $this->showSuccessAlert(__("Saved successfully!"));
            $this->goback();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Save failed!"));
        }
    }
}