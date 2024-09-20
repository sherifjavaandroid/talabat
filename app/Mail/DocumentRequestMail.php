<?php

namespace App\Mail;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $documentRequest;
    public $isVendor;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($documentRequest)
    {
        //
        $this->documentRequest = $documentRequest;
        $this->isVendor = $documentRequest->model_type == Vendor::class;
        $this->locale = setting('locale', 'en');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__("Document Request"))->view('view.emails.document_request');
    }
}
