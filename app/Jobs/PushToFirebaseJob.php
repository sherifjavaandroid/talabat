<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Traits\FirebaseAuthTrait;

class PushToFirebaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use FirebaseAuthTrait;

    public $order;
    public $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order, $user)
    {
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //check if the node already exsits on firebase firestore
        try {

            $payload = [
                'status' => $this->order->status,
                'code' => $this->order->code,
                'amount' => $this->order->total,
            ];

            //add driver id too
            if (!empty($this->order->driver_id)) {
                $payload['driver_id'] = $this->order->driver_id;
            }


            $firestoreClient = $this->getFirebaseStoreClient();
            $firestoreClient->updateDocument(
                "orders/" . $this->order->code . "",
                $payload
            );
        } catch (\Exception $ex) {
            logger("Error creating/updating firebase firestore document", [$ex->getMessage() ?? $ex]);
        }
    }
}
