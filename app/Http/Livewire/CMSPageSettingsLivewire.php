<?php

namespace App\Http\Livewire;

use App\Models\ContentPage;
use Illuminate\Support\Facades\DB;


class CMSPageSettingsLivewire extends BaseLivewireComponent
{
    public $model = ContentPage::class;
    public $name;
    public $slug;
    public $content;
    public $active;

    protected $rules = [
        'name' => 'required',
        'slug' => ['required', 'regex:/^[a-zA-Z\-]+$/', 'unique:content_pages,slug'],
        'content' => 'required',
        'active' => 'nullable|boolean',
    ];

    public function getMessages()
    {
        return  [
            'slug.regex' => __('The slug may only contain letters and dashes.'),

        ];
    }

    public function render()
    {
        return view('livewire.settings.cms-page');
    }



    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            $model = new ContentPage();
            $model->name = $this->name;
            $model->slug = $this->slug;
            $model->content = $this->content;
            $model->is_active = $this->active;
            $model->save();
            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__('Created successfully'));
            $this->emit('refreshTable');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showErrorAlert($e->getMessage() ?? __('Creation failed!'));
        }
    }


    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->slug = $this->selectedModel->slug;
        $this->content = $this->selectedModel->content;
        $this->active = $this->selectedModel->is_active;
        //
        $content = $this->selectedModel->content;
        $summernoteId = "editContent";
        $this->emit('loadSummerNote', $summernoteId, $content);
        //clear previous validation errors
        $this->resetErrorBag();
        $this->emit('showEditModal');
    }


    public function update()
    {

        $data = $this->validate([
            'name' => 'required',
            'slug' =>   ['required', 'regex:/^[a-zA-Z\-]+$/', 'unique:content_pages,slug,' . $this->selectedModel->id . ''],
            'content' => 'required',
            'active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();
            $this->selectedModel->name = $this->name;
            $this->selectedModel->slug = $this->slug;
            $this->selectedModel->content = $this->content;
            $this->selectedModel->is_active = $this->active;
            $this->selectedModel->save();
            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__('Updated successfully'));
            $this->emit('refreshTable');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showErrorAlert($e->getMessage() ?? __('Update failed!'));
        }
    }
}
