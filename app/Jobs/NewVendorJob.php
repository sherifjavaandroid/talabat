<?php

namespace App\Jobs;

use App\Mail\NewVendorMail;
use App\Services\MailHandlerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class NewVendorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        MailHandlerService::sendMail(new NewVendorMail($this->vendor), $this->vendor->email);
        // \Mail::to($this->vendor->email)
        //     ->send(new NewVendorMail($this->vendor));
    }
}
