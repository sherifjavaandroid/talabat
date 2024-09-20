<?php

namespace App\Http\Livewire\Misc;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\Onboarding;
use Exception;
use Illuminate\Support\Facades\DB;

class OnboardingLivewire extends BaseLivewireComponent
{

    //
    public $model = Onboarding::class;

    //
    public $title;
    public $description;
    public $in_order;
    public $is_active;
    public $type;
    public $types = [];


    public function render()
    {
        if (empty($this->types)) {
            $this->types = Onboarding::getPossibleEnumValues("type");
        }
        //
        return view('livewire.misc.onboarding');
    }



    public function save()
    {
        //validate
        $this->validate(
            [
                "title" => "required:string",
                "description" => "required:string",
                "photo" => "required|image|max:" . setting("filelimit.banner", 2048) . "",
            ],
        );

        try {

            $this->isDemo();
            DB::beginTransaction();
            $model = new Onboarding();
            $model->title = $this->title;
            $model->description = $this->description;
            $model->type = $this->type;
            $model->in_order = $this->in_order ?? 1;
            $model->is_active = $this->is_active ?? false;
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Created successfully!"));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Creation failed!"));
        }
    }

    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->title = $this->selectedModel->title;
        $this->description = $this->selectedModel->description;
        $this->type = $this->selectedModel->type;
        $this->in_order = $this->selectedModel->in_order;
        $this->is_active = $this->selectedModel->is_active;
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate(
            [
                "title" => "required:string",
                "description" => "required:string",
                "photo" => "sometimes|nullable|image|max:" . setting("filelimit.banner", 2048) . "",
            ]
        );

        try {

            $this->isDemo();
            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->title = $this->title;
            $model->description = $this->description;
            $model->type = $this->type;
            $model->in_order = $this->in_order ?? 1;
            $model->is_active = $this->is_active ?? false;
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection();
                $this->photo = null;
            }

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Updated successfully!"));
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? __("Update failed!"));
        }
    }
}
