<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vendor;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($vendor)
    {
        //
        $this->vendor = $vendor;
        $this->locale = setting('locale', 'en');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__("Account Updated"))->view('view.emails.vendor_update');
    }
}
