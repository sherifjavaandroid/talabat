<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Day;
use App\Models\DeliveryZone;
use App\Models\VendorType;
use App\Models\Fee;
use App\Models\VendorManager;
use Exception;
use Illuminate\Support\Facades\DB;

class VendorLivewire extends VendorTimingLivewire
{

    //
    public $model = Vendor::class;
    public $showDayAssignment = false;
    public $showNewDayAssignment = false;

    //
    public $documents = [];
    public $name;
    public $description;
    public $base_delivery_fee;
    public $delivery_fee;
    public $charge_per_km;
    public $delivery_range;
    public $min_order;
    public $max_order;
    public $phone;
    public $email;
    public $address;
    public $latitude;
    public $longitude;
    public $commission;
    public $tax;
    public $pickup;
    public $delivery;
    public $isActive;
    public $featured;
    public $auto_assignment;
    public $auto_accept;
    public $allow_schedule_order;
    public $has_sub_categories;
    public $use_subscription = false;
    public $vendor_type_id;
    public $vendorTypes;
    public $isPackageVendor = false;
    public $isServiceVendor = false;
    public $deliveryZones;
    public $has_drivers = false;
    public $prepare_time;
    public $prepare_time_unit = "minutes";
    public $delivery_time;
    public $delivery_time_unit = "minutes";
    public $timeUnits = ["minutes", "hours", "days", "weeks", "months"];
    public $in_order = 1;

    //
    public $managersIDs;
    public $deliveryZonesIDs;
    public $categoriesIDs;
    public $feesIDs;
    //
    public $categorySearchClause = [];
    public $selectedCategories = [];
    //fees
    public $feeSearchClause = [];
    public $selectedFees = [];


    public function rules()
    {
        return [
            "name" => "required|string",
            "description" => "required|string",
            "base_delivery_fee" => 'nullable|sometimes|numeric|required_if:is_package_vendor,0,false',
            "delivery_fee" => 'nullable|sometimes|numeric|required_if:is_package_vendor,0,false',
            "delivery_range" => 'nullable|sometimes|numeric|required_if:is_package_vendor,0,false',
            "vendor_type_id" => 'required|exists:vendor_types,id',
            "email" => "required|email|unique:vendors,email",
            "address" => "required|string",
            "latitude" => "required|numeric",
            "longitude" => "required|numeric",
            "commission" => "nullable|sometimes|numeric",
            "photo" => "required|image|max:1024",
            "secondPhoto" => "required|image|max:2048",
            "phone" => "required|phone:" . setting('countryCode', "INTERNATIONAL") . "",
        ];
    }

    public function messages()
    {
        return [
            "photo.max" => __("Logo must be not be more than 1MB"),
            "photo.required" => __("Logo is required"),
            "secondPhoto.max" => __("Feature Image must be not be more than 2MB"),
            "secondPhoto.required" => __("Feature Image is required"),
            "email.unique" => __("Email already used by another vendor"),
            "phone.phone" => __("Phone Number is invalid"),
        ];
    }


    public function getListeners()
    {
        return $this->listeners + [
            "deliveryZonesChange" => "deliveryZonesChange",
            "feesChange" => "feesChange",
        ];
    }


    public function render()
    {
        if (empty($this->vendorTypes)) {
            $this->vendorTypes = VendorType::active()->assignable()->get();
        }
        if (empty($this->deliveryZones)) {
            $this->deliveryZones = DeliveryZone::active()->get();
        }
        return view('livewire.vendors');
    }


    public function updatedVendorTypeId($value)
    {
        //
        $vendorType = VendorType::find($value);
        $this->isPackageVendor = $vendorType->slug == "parcel";
        $this->isServiceVendor = $vendorType->slug == "service";
        $this->categorySearchClause = ["vendor_type_id" => $value];
        $this->emit('categoryQueryClasueUpdate', $this->categorySearchClause);
        $this->updateDeliveryZoneSelector();
    }

    public function showCreateModal()
    {
        $this->reset();
        $this->vendorTypes = VendorType::active()->get();
        $this->vendor_type_id = $this->vendorTypes->first()->id;
        $this->updatedVendorTypeId($this->vendor_type_id);
        //fees
        $this->feesIDs = [];
        $fees = Fee::get();
        $this->showSelect2("#feesSelect2", $this->feesIDs, "feesChange", $fees);
        $this->emit('initialAddressSelected', '');
        $this->emit('loadSummerNote', "newContent", "");
        //
        parent::showCreateModal();
    }

    public function save()
    {
        //validate
        $rules = $this->getRules();
        $rules["photo"] = "required|image|max:" . setting("filelimit.vendor_logo", 2048) . "";
        $rules["secondPhoto"] = "required|image|max:" . setting("filelimit.vendor_feature", 2048) . "";
        $this->validate($rules);

        try {

            DB::beginTransaction();
            $model = new Vendor();
            $model->name = $this->name;
            $model->description = $this->description;
            $model->base_delivery_fee = $this->base_delivery_fee;
            $model->delivery_fee = $this->delivery_fee;
            $model->charge_per_km = $this->charge_per_km;
            $model->delivery_range = $this->delivery_range;
            $model->phone = $this->phone;
            $model->email = $this->email;
            $model->address = $this->address;
            $model->latitude = $this->latitude;
            $model->longitude = $this->longitude;
            //if commission is empty, set it to null
            if (empty($this->commission)) {
                $this->commission = null;
            }
            $model->commission = $this->commission;
            $model->tax = $this->tax;
            $model->pickup = $this->pickup ?? 0;
            $model->delivery = $this->delivery ?? 0;
            $model->min_order = $this->min_order;
            $model->max_order = $this->max_order;
            $model->is_active = $this->isActive ?? false;
            $model->featured = $this->featured ?? false;
            $model->auto_assignment = $this->auto_assignment ?? false;
            $model->auto_accept = $this->auto_accept ?? false;
            $model->allow_schedule_order = $this->allow_schedule_order ?? false;
            $model->has_sub_categories = $this->has_sub_categories ?? false;
            $model->vendor_type_id = $this->vendor_type_id;
            $model->use_subscription = $this->use_subscription ?? false;
            $model->has_drivers = $this->has_drivers ?? false;
            $model->prepare_time = $this->prepare_time;
            $model->prepare_time_unit = $this->prepare_time_unit ?? $this->timeUnits[0];
            $model->delivery_time = $this->delivery_time;
            $model->delivery_time_unit = $this->delivery_time_unit ?? $this->timeUnits[0];
            $model->in_order = $this->in_order;
            //creator
            $model->creator_id = \Auth::id();
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())
                    ->usingFileName(genFileName($this->photo))
                    ->toMediaCollection("logo");
                $this->photo = null;
            }

            if ($this->secondPhoto) {

                $model->clearMediaCollection();
                $model->addMedia($this->secondPhoto->getRealPath())
                    ->usingFileName(genFileName($this->secondPhoto))
                    ->toMediaCollection("feature_image");
                $this->secondPhoto = null;
            }

            //if documents is not empty
            if (!empty($this->documents)) {
                foreach ($this->documents as $document) {
                    $model->addMedia($document->getRealPath())
                        ->usingFileName(genFileName($document))
                        ->toMediaCollection("documents");
                }
                $this->documents = [];
            }

            //
            $model->categories()->attach($this->categoriesIDs);
            $model->delivery_zones()->sync($this->deliveryZonesIDs);
            $model->fees()->sync($this->feesIDs);

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Vendor") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vendor") . " " . __('creation failed!'));
        }
    }

    // Updating model
    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->description = $this->selectedModel->description;
        $this->base_delivery_fee = $this->selectedModel->getRawOriginal("base_delivery_fee");
        $this->delivery_fee = $this->selectedModel->getRawOriginal("delivery_fee");
        $this->delivery_range = $this->selectedModel->getRawOriginal("delivery_range");
        $this->phone = $this->selectedModel->phone;
        $this->email = $this->selectedModel->email;
        $this->address = $this->selectedModel->address;
        $this->latitude = $this->selectedModel->latitude;
        $this->longitude = $this->selectedModel->longitude;
        //get original commission
        $this->commission = $this->selectedModel->getRawOriginal("commission");
        $this->tax = $this->selectedModel->tax;
        $this->pickup = $this->selectedModel->pickup;
        $this->delivery = $this->selectedModel->delivery;
        $this->min_order = $this->selectedModel->min_order;
        $this->max_order = $this->selectedModel->max_order;
        $this->isActive = $this->selectedModel->is_active;
        $this->featured = $this->selectedModel->featured;
        $this->vendor_type_id = $this->selectedModel->vendor_type_id;
        $this->auto_assignment = $this->selectedModel->auto_assignment;
        $this->auto_accept = $this->selectedModel->auto_accept;
        $this->allow_schedule_order = $this->selectedModel->allow_schedule_order;
        $this->has_sub_categories = $this->selectedModel->has_sub_categories;
        $this->charge_per_km = $this->selectedModel->getRawOriginal("charge_per_km");
        $this->use_subscription = $this->selectedModel->use_subscription ?? false;
        $this->has_drivers = $this->selectedModel->has_drivers ?? false;
        $this->prepare_time = $this->selectedModel->prepare_time;
        $this->prepare_time_unit = $this->selectedModel->prepare_time_unit;
        $this->delivery_time = $this->selectedModel->delivery_time;
        $this->delivery_time_unit = $this->selectedModel->delivery_time_unit;
        $this->in_order = $this->selectedModel->in_order;


        //
        $this->updatedVendorTypeId($this->selectedModel->vendor_type_id);

        $this->categoriesIDs = $this->selectedModel->categories()->pluck('category_id');
        $this->deliveryZonesIDs = $this->selectedModel->delivery_zones()->pluck('delivery_zone_id');
        $this->selectedCategories = Category::whereIn('id', $this->categoriesIDs)->get();

        $this->updateDeliveryZoneSelector();
        //fees
        $this->feesIDs = $this->selectedModel->plain_fees()->pluck('fee_id');
        $fees = Fee::get();
        $this->showSelect2("#editFeesSelect2", $this->feesIDs, "feesChange", $fees);
        $this->emit('initPhone', json_encode(["phoneEdit", "phone", $this->phone]));
        //
        $this->emit('loadSummerNote', "editContent", $this->description);
        //
        $this->emit('showEditModal');
        $this->emit('initialAddressSelected', $this->address);
    }

    public function update()
    {
        //validate
        $this->validate(
            [
                "name" => "required|string",
                "description" => "required|string",
                "phone" => "required|phone:" . setting('countryCode', "INTERNATIONAL") . "",
                "email" => "required|email|unique:vendors,email," . $this->selectedModel->id . "",
                "address" => "required|string",
                "latitude" => "required|numeric",
                "longitude" => "required|numeric",
                "photo" => "nullable|sometimes|image|max:" . setting("filelimit.vendor_logo", 2048) . "",
                "secondPhoto" => "nullable|sometimes|image|max:" . setting("filelimit.vendor_feature", 2048) . "",
            ]
        );

        try {

            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->name = $this->name;
            $model->description = $this->description;
            $model->base_delivery_fee = $this->base_delivery_fee;
            $model->delivery_fee = $this->delivery_fee;
            $model->charge_per_km = $this->charge_per_km;
            $model->delivery_range = $this->delivery_range;
            $model->phone = $this->phone;
            $model->email = $this->email;
            $model->address = $this->address;
            $model->latitude = $this->latitude;
            $model->longitude = $this->longitude;
            //if commission is empty, set it to null
            if (empty($this->commission)) {
                $this->commission = null;
            }
            $model->commission = $this->commission;
            $model->tax = $this->tax;
            $model->pickup = $this->pickup;
            $model->delivery = $this->delivery;
            $model->min_order = $this->min_order;
            $model->max_order = $this->max_order;
            $model->is_active = $this->isActive;
            $model->featured = $this->featured;
            $model->vendor_type_id = $this->vendor_type_id;
            $model->auto_assignment = $this->auto_assignment;
            $model->auto_accept = $this->auto_accept;
            $model->allow_schedule_order = $this->allow_schedule_order;
            $model->has_sub_categories = $this->has_sub_categories;
            $model->use_subscription = $this->use_subscription;
            $model->has_drivers = $this->has_drivers;
            $model->prepare_time = $this->prepare_time;
            $model->prepare_time_unit = $this->prepare_time_unit ?? $this->timeUnits[0];
            $model->delivery_time = $this->delivery_time;
            $model->delivery_time_unit = $this->delivery_time_unit ?? $this->timeUnits[0];
            $model->in_order = $this->in_order;
            $model->save();

            //sync the delivery_zones

            if ($this->photo) {

                $model->clearMediaCollection("logo");
                $model->addMedia($this->photo->getRealPath())
                    ->usingFileName(genFileName($this->photo))
                    ->toMediaCollection("logo");
                $this->photo = null;
            }

            if ($this->secondPhoto) {

                $model->clearMediaCollection("feature_image");
                $model->addMedia($this->secondPhoto->getRealPath())
                    ->usingFileName(genFileName($this->secondPhoto))
                    ->toMediaCollection("feature_image");
                $this->secondPhoto = null;
            }

            //if documents is not empty
            if (!empty($this->documents)) {
                foreach ($this->documents as $document) {
                    $model->addMedia($document->getRealPath())
                        ->usingFileName(genFileName($document))
                        ->toMediaCollection("documents");
                }
                $this->documents = [];
            }

            //
            $model->categories()->sync($this->categoriesIDs);
            $model->delivery_zones()->sync($this->deliveryZonesIDs);
            $model->fees()->sync($this->feesIDs);

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Vendor") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vendor") . " " . __('updated failed!'));
        }
    }

    // Assigning managers
    public function initiateAssign($id)
    {
        $this->selectedModel = $this->model::find($id);
        if (\Schema::hasTable("vendor_managers")) {
            $this->managersIDs = VendorManager::where('vendor_id', $this->selectedModel->id)->get()->pluck('user_id');
        } else {
            $this->managersIDs = $this->selectedModel->managers()->pluck('id');
        }
        $managers =  User::manager()->get();
        $this->showSelect2("#managersSelect2", $this->managersIDs, "managersChange", $managers);
        $this->emit('showAssignModal');
    }


    public function managersChange($data)
    {
        $this->managersIDs = $data;
    }

    public function deliveryZonesChange($data)
    {
        $this->deliveryZonesIDs = $data;
    }
    public function categoriesChange($data)
    {
        $this->categoriesIDs = $data;
    }

    public function feesChange($data)
    {
        $this->feesIDs = $data;
    }


    public function updateDeliveryZoneSelector()
    {
        $deliveryZones = DeliveryZone::active()->get();
        if ($this->showCreate) {
            $this->showSelect2("#deliveryZonesSelect2", $this->deliveryZonesIDs, "deliveryZonesChange", $deliveryZones);
        } else {
            $this->showSelect2("#editDeliveryZonesSelect2", $this->deliveryZonesIDs, "deliveryZonesChange", $deliveryZones);
        }
    }






    //
    public function assignManagers()
    {

        try {

            DB::beginTransaction();

            //remove all managers
            User::where('vendor_id', $this->selectedModel->id)
                ->update(['vendor_id' => null]);

            //clear old vendor manager record
            // \DB::table('vendor_managers')->where('vendor_id', $this->selectedModel->id)->delete();
            VendorManager::where('vendor_id', $this->selectedModel->id)->delete();

            //assigning
            foreach ($this->managersIDs as $managerId) {
                $manager = User::findorfail($managerId);
                $manager->vendor_id = $this->selectedModel->id;
                $manager->save();
                //
                VendorManager::firstOrCreate([
                    "vendor_id" => $this->selectedModel->id,
                    "user_id" => $managerId,
                ], []);
            }

            DB::commit();
            $this->emit('dismissModal');
            $this->showSuccessAlert(__("Vendor Managers") . " " . __('created successfully!'));
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vendor Managers") . " " . __('creation failed!'));
        }
    }

    //
    public function autocompleteAddressSelected($data)
    {
        $this->address = $data["address"];
        $this->latitude = $data["latitude"];
        $this->longitude = $data["longitude"];
    }




    //
    public function requestDocuments()
    {
        try {
            $this->isDemo();
            $documentRequest = new \App\Models\DocumentRequest();
            $documentRequest->model_id = $this->selectedModel->id;
            $documentRequest->model_type = $this->model;
            $documentRequest->status = "requested";
            $documentRequest->save();
            $this->dismissModal();
            $this->showSuccessAlert(__("Vendor") . " " . __('document request sent successfully!'));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Vendor") . " " . __('document request failed!'));
        }
    }

    public function cancelDocumentRequest()
    {

        try {
            $this->isDemo();
            $documentRequest = $this->selectedModel->document_request()->where('status', 'requested')->first();
            if ($documentRequest) {
                $documentRequest->delete();
            }
            $this->dismissModal();
            $this->showSuccessAlert(__("Vendor") . " " . __('document request cancelled successfully!'));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Vendor") . " " . __('document request cancellation failed!'));
        }
    }
}
