<?php

namespace App\Traits;

trait DocumentRequestTrait
{

    public function getDocumentRequestedAttribute()
    {
        //check if there is a record of the document request of this model and status is requested
        $documentRequest = $this->document_request()->where('status', 'requested')->first();
        if ($documentRequest) {
            return true;
        } else {
            return false;
        }
    }

    public function getPendingDocumentApprovalAttribute()
    {
        //check if there is a record of the document request of this model and status is requested
        $documentRequest = $this->document_request()->where('status', 'pending')->first();
        if ($documentRequest) {
            return true;
        } else {
            return false;
        }
    }


    //
    public function document_request()
    {
        return $this->morphOne('App\Models\DocumentRequest', 'model');
    }
}
