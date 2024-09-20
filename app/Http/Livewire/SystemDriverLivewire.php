<?php

namespace App\Http\Livewire;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SystemDriverLivewire extends BaseLivewireComponent
{

    //
    public $model = User::class;
    //
    public $name;
    public $email;
    public $phone;
    public $password;
    public $commission;


    public function rules()
    {
        return [
            "name" => "required|string",
            "email" => "required|email|unique:users",
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
        return view('livewire.system_drivers');
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
            $user->assignRole("driver");
            //update wallet
            $user->updateWallet(0);

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Driver") . " " . __('created successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            logger("User Create Error", [$error]);
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Driver") . " " . __('creation failed!'));
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
            $this->commission = $this->selectedModel->getRawOriginal('commission');
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
            DB::commit();
            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Driver") . " " . __('updated successfully!'));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Driver") . " " . __('updated failed!'));
        }
    }


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
            $this->showSuccessAlert(__("Driver") . " " . __('document request sent successfully!'));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Driver") . " " . __('document request failed!'));
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
            $this->showSuccessAlert(__("Driver") . " " . __('document request cancelled successfully!'));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Driver") . " " . __('document request cancellation failed!'));
        }
    }
}
