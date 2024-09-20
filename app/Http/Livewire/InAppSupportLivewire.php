<?php

namespace App\Http\Livewire;


class InAppSupportLivewire extends BaseLivewireComponent
{

    public $inappSupportCode;

    public function mount()
    {
        $this->inappSupportCode = setting("inapp.support", "");
    }

    public function render()
    {
        return view('livewire.inapp_support');
    }

    public function save()
    {
        try {
            $this->isDemo();
            setting([
                "inapp.support" => $this->inappSupportCode,
            ])->save();
            $this->showSuccessAlert(__("In-App Support") . " " . __('updated successfully!'));
        } catch (\Exception $error) {
            \DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("User") . " " . __('updated failed!'));
        }
    }
}
