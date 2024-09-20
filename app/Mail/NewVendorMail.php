<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewVendorMail extends Mailable
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
        return $this->subject(__("New Account"))->view('view.emails.new_vendor');
    }
}
