<?php

namespace App\Http\Livewire\Auth;

use App\Http\Livewire\BaseLivewireComponent;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Propaganistas\LaravelPhone\PhoneNumber;


class RegisterLivewire extends BaseLivewireComponent
{


    public $type = "";
    public $vendor_name;
    public $vendor_email;
    public $vendor_phone;
    public $vendorTypes;
    public $vendor_type_id;
    //address, latitude and longitude
    public $address;
    public $latitude;
    public $longitude;

    public $name;
    public $phone;
    public $email;
    public $referalCode;
    public $password;

    public $agreedVendor;
    public $vendorDocuments;


    public function getListeners()
    {
        return $this->listeners + [
            'autocompleteAddressSelected' => 'autocompleteAddressSelected',
            'vendorDocumentsUploaded' => 'vendorDocumentsUploaded',
        ];
    }

    public function render()
    {
        if ($this->vendorTypes == null) {
            $this->vendorTypes = VendorType::active()->inorder()->where('slug', '!=', 'taxi')->get();
        }
        if ($this->vendor_type_id == null) {
            $this->vendor_type_id = $this->vendorTypes->first()->id ?? "";
        }
        return view('livewire.auth.register.register')->layout('layouts.guest');
    }

    public function vendorDocumentsUploaded($documents)
    {
        $this->vendorDocuments = $documents;
    }

    public function autocompleteAddressSelected($data)
    {
        $this->address = $data["address"];
        $this->latitude = $data["latitude"];
        $this->longitude = $data["longitude"];
    }

    public function signUp()
    {


        $this->validate(
            [
                "agreedVendor" => "accepted",
                "vendor_name" => "required",
                "vendor_email" => "required|email|unique:vendors,email",
                "vendor_phone" => "required|phone:" . setting('countryCode', "GH") . "|unique:vendors,phone",
                "vendor_type_id" => "required|exists:vendor_types,id",
                "address" => "required",
                "name" => "required",
                "email" => "required|email|unique:users",
                "phone" => "required|phone:" . setting('countryCode', "GH") . "|unique:users",
                "password" => "required|string|min:6",
                "vendorDocuments" => "required",
            ]
        );

        //
        try {

            //
            $phone = new PhoneNumber($this->phone);
            $vendorPhone = new PhoneNumber($this->vendor_phone);
            //
            $user = User::where('phone', $phone)->first();
            if (!empty($user)) {
                $this->showErrorAlert(__("Account with phone already exists"), 100000);
                return;
            }


            DB::beginTransaction();
            //
            $user = new User();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $phone;
            $user->creator_id = Auth::id();
            $user->commission = Null;
            $user->password = Hash::make($this->password);
            $user->is_active = false;
            $user->save();
            //assign role
            $user->assignRole('manager');

            //create vendor
            $vendor = new Vendor();
            $vendor->name = $this->vendor_name;
            $vendor->email = $this->vendor_email;
            $vendor->phone = $vendorPhone;
            $vendor->is_active = false;
            $vendor->vendor_type_id = $this->vendor_type_id;
            //
            $vendor->address = $this->address;
            $vendor->latitude = $this->latitude;
            $vendor->longitude = $this->longitude;
            $vendor->save();

            if ($this->vendorDocuments) {

                $vendor->clearMediaCollection("documents");
                foreach ($this->vendorDocuments as $vendorDocument) {
                    $vendor->addMedia($vendorDocument)
                        ->usingFileName(genFileName($vendorDocument))
                        ->toMediaCollection("documents");
                }
                $this->vendorDocuments = null;
            }

            //assign manager to vendor
            $user->vendor_id = $vendor->id;
            $user->save();

            DB::commit();
            $this->showSuccessAlert(__("Account Created Successfully. Your account will be reviewed and you will be notified via email/sms when account gets approved. Thank you"), 100000);
            $this->reset();
        } catch (\Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("An error occurred please try again later"), 100000);
        }
    }
}
