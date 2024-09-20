<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Traits\FirebaseAuthTrait;

class ClearDriverFirebaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use FirebaseAuthTrait;

    public $expiredDriverNewOrder;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($expiredDriverNewOrder)
    {
        $this->expiredDriverNewOrder = $expiredDriverNewOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $firestoreClient = $this->getFirebaseStoreClient();
        $driverNewOrderAlertRef = "driver_new_order/" . $this->expiredDriverNewOrder["id"] . "";
        $firestoreClient->deleteDocument($driverNewOrderAlertRef);
    }
}
