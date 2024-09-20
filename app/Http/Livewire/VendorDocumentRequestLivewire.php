<?php

namespace App\Http\Livewire;

use App\Models\DocumentRequest;

class VendorDocumentRequestLivewire extends BaseLivewireComponent
{
    public $documents = [];
    public $model = DocumentRequest::class;
    public function getListeners()
    {
        return $this->listeners + [
            'initiateDocumentUpload' => 'initiateDocumentUpload',
        ];
    }


    public function render()
    {
        return view('livewire.vendor_document_request');
    }

    public function initiateDocumentUpload($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->showEditModal();
    }

    public function uploadDocument()
    {

        //validate that the document is not empty
        $this->validate([
            'documents' => 'required'
        ]);

        try {

            foreach ($this->documents ?? [] as $document) {
                $this->selectedModel->addMedia($document->getRealPath())
                    ->usingFileName(genFileName($document))
                    ->toMediaCollection("documents");
            }
            //
            $this->documents = [];
            $this->dismissModal();
            $this->showSuccessAlert(__("Document uploaded successfully"));
        } catch (\Exception $e) {
            $this->showErrorAlert($e->getMessage());
        }
    }
}
