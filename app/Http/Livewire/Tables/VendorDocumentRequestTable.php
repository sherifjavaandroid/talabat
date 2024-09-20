<?php

namespace App\Http\Livewire\Tables;


use App\Models\DocumentRequest;
use App\Models\Vendor;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VendorDocumentRequestTable extends BaseDataTableComponent
{

    public $model = DocumentRequest::class;

    public function query()
    {
        return DocumentRequest::where('model_type', Vendor::class);
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), "id")->searchable()->sortable(),
            Column::make(__('Vendor'), 'model.name')->searchable()->sortable(),
            Column::make(__('Status'), 'status')->sortable(),
            $this->actionsColumn('components.buttons.document_request_actions'),
        ];
    }

    public function initiateActivate($id)
    {
        $this->selectedModel = $this->model::find($id);

        $this->confirm(__('Approve'), [
            'icon' => 'question',
            'toast' => false,
            'text' =>  __('Are you sure you want to approve the submitted documents?'),
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __("Cancel"),
            'confirmButtonText' => __("Yes"),
            'onConfirmed' => 'activateModel',
            'onCancelled' => 'cancelled'
        ]);
    }

    public function initiateDeactivate($id)
    {
        $this->selectedModel = $this->model::find($id);

        $this->confirm(__('Reject'), [
            'icon' => 'question',
            'toast' => false,
            'text' =>  __('Are you sure you want to reject the submitted documents?'),
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __("Cancel"),
            'confirmButtonText' => __("Yes"),
            'onConfirmed' => 'deactivateModel',
            'onCancelled' => 'cancelled'
        ]);
    }


    public function activateModel()
    {
        try {
            if ($this->checkDemo) {
                $this->isDemo();
            }
            $this->selectedModel->status = 'approved';
            $this->selectedModel->save();
            $this->showSuccessAlert(__("Approved"));
        } catch (\Exception $error) {
            $this->showErrorAlert(__("Failed"));
        }
    }

    public function deactivateModel()
    {
        try {
            if ($this->checkDemo) {
                $this->isDemo();
            }
            $this->selectedModel->status = 'rejected';
            $this->selectedModel->save();
            $this->showSuccessAlert(__("Rejected"));
        } catch (\Exception $error) {
            $this->showErrorAlert(__("Failed"));
        }
    }
}
