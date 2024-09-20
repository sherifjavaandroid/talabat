<?php

namespace App\Jobs;

use App\Services\TaxiOrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Traits\FirebaseAuthTrait;
use App\Traits\FirebaseMessagingTrait;

class TaxiOrderUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use FirebaseAuthTrait, FirebaseMessagingTrait;


    public $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $taxiOrderService = new TaxiOrderService();
        $taxiOrderService->updateTaxiOrderToFirebaseFirestore($this->order);
    }
}
