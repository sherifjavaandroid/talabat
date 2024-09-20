<?php

namespace App\Http\Livewire;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DriverLivewire extends BaseLivewireComponent
{

    //
    public $model = User::class;

    //
    public $name;
    public $email;
    public $phone;
    public $password;
    public $role;
    public $commission;

    protected $rules = [
        "name" => "required|string",
        "email" => "required|email|unique:users",
        "phone" => "required|unique:users",
        "password" => "sometimes|nullable|string",
        "commission" => "sometimes|nullable|numeric",
    ];


    protected $messages = [
        "email.unique" => "Email already associated with any account",
        "phone.unique" => "Phone Number already associated with any account",
    ];

    public function render()
    {
        return view('livewire.drivers');
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
            $user->commission = $this->commission ?? 0.00;
            $user->password = Hash::make($this->password);
            $user->vendor_id = \Auth::user()->vendor_id;
            $user->save();
            $user->assignRole('driver');

            //update wallet
            $user->updateWallet(0);

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("User") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("User") . " " . __('creation failed!'));
        }
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->email = $this->selectedModel->email;
        $this->phone = $this->selectedModel->phone;
        $this->emit('initPhone', json_encode(["phoneEdit", "phone", $this->phone ?? ""]));
        $this->role = $this->selectedModel->role_id;
        //get commission original value
        $this->commission = $this->selectedModel->getRawOriginal('commission');
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate(
            [
                "name" => "required|string",
                "email" => "required|email|unique:users,email," . $this->selectedModel->id . "",
                "phone" => "required|unique:users,phone," . $this->selectedModel->id . "",
                "password" => "sometimes|nullable|string",
                "commission" => "sometimes|nullable|numeric",
            ]
        );

        try {

            DB::beginTransaction();
            $user = $this->selectedModel;
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->commission = $this->commission;
            if (!empty($this->password)) {
                $user->password = Hash::make($this->password);
            }
            $user->save();

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
}
