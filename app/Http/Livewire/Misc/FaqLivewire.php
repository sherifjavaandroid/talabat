<?php

namespace App\Http\Livewire\Misc;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\Faq;
use Exception;
use Illuminate\Support\Facades\DB;

class FaqLivewire extends BaseLivewireComponent
{

    //
    public $model = Faq::class;

    //
    public $title;
    public $body;
    public $in_order;
    public $is_active;
    public $type;
    public $types = [];


    public function render()
    {
        if (empty($this->types)) {
            $this->types = array_merge(['all'], Faq::getPossibleEnumValues("type"));
        }
        //
        return view('livewire.misc.faq');
    }


    public function showCreateModal()
    {
        $this->emit('prepCustomWYSISYG', [""]);
        $this->showCreate = true;
        $this->stopRefresh = true;
    }

    public function save()
    {
        //validate
        $this->validate(
            [
                "title" => "required:string",
                "body" => "required:string",
            ],
        );

        try {

            $this->isDemo();
            DB::beginTransaction();
            $model = new Faq();
            $model->title = $this->title;
            $model->body = $this->body;
            $model->type = $this->type == "all" ? null : $this->type;
            $model->in_order = $this->in_order ?? 1;
            $model->is_active = $this->is_active ?? false;
            $model->save();
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
        $this->body = $this->selectedModel->body;
        $this->type = $this->selectedModel->type == null ? "all" : $this->selectedModel->type;
        $this->in_order = $this->selectedModel->in_order;
        $this->is_active = $this->selectedModel->is_active;
        $this->emit('prepCustomWYSISYG', [$this->body]);
        $this->emit('showEditModal');
    }

    public function update()
    {
        //validate
        $this->validate(
            [
                "title" => "required:string",
                "body" => "required:string",
            ]
        );

        try {

            $this->isDemo();
            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->title = $this->title;
            $model->body = $this->body;
            $model->type = $this->type == "all" ? null : $this->type;
            $model->in_order = $this->in_order ?? 1;
            $model->is_active = $this->is_active ?? false;
            $model->save();
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
