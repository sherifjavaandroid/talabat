<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Vendor;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserLivewire extends BaseLivewireComponent
{

    //
    public $model = User::class;
    public $selectRole;

    //
    public $name;
    public $email;
    public $phone;
    public $password;
    public $role;
    public $commission;
    public $walletBalance;
    //
    public $vendorsIDs;
    public $vendors = [];

    public function rules()
    {
        return [
            "role" => "required",
            "name" => "required|string",
            "email" => "required|email|unique:users",
            // "phone" => "required|unique:users",
            "phone" => "required|phone:" . setting('countryCode', "GH") . "|unique:users",
            "password" => "sometimes|nullable|string",
            "commission" => "sometimes|nullable|numeric",
        ];
    }
    public function messages()
    {
        return [
            "email.unique" => __("Email already associated with any account"),
            "phone.unique" => __("Phone Number already associated with any account"),
            "phone.phone" => __("Phone Number is invalid"),
        ];
    }

    public function render()
    {
        return view('livewire.users', [
            "roles" => $this->getRoles(),
        ]);
    }

    public function getRoles()
    {
        $user = User::find(\Auth::id());
        $roles = [];
        if ($user->hasRole('admin')) {
            $roles = Role::get();
        } else {
            $roles = Role::where('name', 'not like', '%admin%')->get();
        }

        $roles = $roles->map(function ($role) {
            return [
                "id" => $role->name,
                "name" => $role->name,
            ];
        });
        return $roles;
    }


    public function sortList($role)
    {
        $this->selectRole = $role;
        $this->emit('filterUsers', $role);
    }

    public function showCreateModal()
    {
        $this->reset();
        $this->role = $this->getRoles()[0]["name"];
        $this->showCreate = true;
    }

    public function save()
    {

        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $user = new User();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->creator_id = \Auth::id();
            $user->commission = $this->commission;
            $user->password = Hash::make($this->password);
            $user->save();
            //client role if not set
            $user->assignRole($this->role ?? "client");

            //update wallet
            $user->updateWallet($this->walletBalance);

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("User") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            logger("User Create Error", [$error]);
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("User") . " " . __('creation failed!'));
        }
    }

    public function initiateEdit($id)
    {
        try {

            $this->isDemo();
            $this->reset();
            $this->selectedModel = $this->model::find($id);
            $this->name = $this->selectedModel->name;
            $this->email = $this->selectedModel->email;
            $this->phone = $this->selectedModel->phone;
            $this->role = $this->selectedModel->role_name;
            $this->commission = $this->selectedModel->getRawOriginal('commission');
            $this->walletBalance = $this->selectedModel->wallet->balance ?? 0.00;
            $this->emit('initPhone', json_encode(["phoneEdit", "phone", $this->phone ?? ""]));
            $this->emit('showEditModal');
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Edit User") . " " . __('failed!'));
        }
    }

    public function update()
    {
        //validate
        $this->validate(
            [
                "name" => "required|string",
                "email" => "required|email|unique:users,email," . $this->selectedModel->id . "",
                // "phone" => "required|unique:users,phone," . $this->selectedModel->id . "",
                "phone" => "required|phone:" . setting('countryCode', "GH") . "|unique:users,phone," . $this->selectedModel->id . "",
                "password" => "sometimes|nullable|string",
                "commission" => "sometimes|nullable|numeric",
            ]
        );

        try {
            $this->isDemo();
            DB::beginTransaction();
            $user = $this->selectedModel;
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->commission = $this->commission ?? 0.00;
            if (!empty($this->password)) {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            $user->assignRole($this->role);


            //update wallet
            $user->updateWallet($this->walletBalance);

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("User") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("User") . " " . __('updated failed!'));
        }
    }


    // Assigning vendors
    public function initiateAssign($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->vendorsIDs = $this->selectedModel->vendors->pluck('id');
        $this->vendors = Vendor::active()->get();
        $this->emit('showAssignModal');
        $this->showSelect2("#vendorsSelect2", $this->vendorsIDs, "vendorsChange", $this->vendors);
    }

    public function assignVendors()
    {
        try {

            DB::beginTransaction();

            //assigning
            foreach ($this->vendorsIDs as $vendorsID) {
                $vendor = Vendor::findorfail($vendorsID);
                $vendor->creator_id = $this->selectedModel->id;
                $vendor->save();
            }

            DB::commit();
            $this->emit('dismissModal');
            $this->showSuccessAlert(__("Vendor City Admin") . " " . __('updated successfully!'));
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Vendor City Admin") . " " . __('updated failed!'));
        }
    }

    public function vendorsChange($data)
    {
        $this->vendorsIDs = $data;
    }
}