<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Traits\FirebaseAuthTrait;
use App\Services\FirestoreRestService;

class ClearFirebaseJob implements ShouldQueue
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
        //
        $canClearFirestore = (bool) setting('clearFirestore', 1);
        // logger("can clear firestore data", [$canClearFirestore]);
        //
        if (in_array($this->order->status, ['failed', 'cancelled', 'delivered', 'completed']) && $canClearFirestore) {
            // logger("clearing firestore data");
            try {
                $firestoreClient = $this->getFirebaseStoreClient();

                $collectionPaths = [
                    "orders/{$this->order->code}/customerDriver/chats/Activity",
                    "orders/{$this->order->code}/driverVendor/chats/Activity",
                    "orders/{$this->order->code}/customerVendor/chats/Activity",
                ];

                foreach ($collectionPaths as $collectionPath) {
                    try {
                        $documents = $firestoreClient->listDocuments($collectionPath)["documents"] ?? [];
                        //logger("Douments", [$documents]);
                        if (!empty($documents)) {
                            foreach ($documents as $document) {
                                $documentPath = $document->getRelativeName();
                                if (substr($documentPath, 0, strlen("/")) === "/") {
                                    $documentPath = substr_replace($documentPath, "", 0, 1);
                                }
                                //logger("document path", [$documentPath]);
                                $firestoreClient->deleteDocument($documentPath);
                            }
                        }
                    } catch (\Exception $ex) {
                        logger("Error listing documents in ==> ", [$collectionPath]);
                        logger("Error", [$ex]);
                    }
                }


                $paths = [
                    "orders/{$this->order->code}/customerDriver/chats",
                    "orders/{$this->order->code}/driverVendor/chats",
                    "orders/{$this->order->code}/customerVendor/chats",
                    "orders/{$this->order->code}",
                ];
                //
                foreach ($paths as $path) {
                    $firestoreClient->deleteDocument($path);
                }



                //now delete the main order node
                $path =  "orders/{$this->order->code}";
                $firestoreClient->deleteDocument($path);
            } catch (\Exception $ex) {
                logger("Error deleting firebase firestore document", [$ex]);
            }
        }

        //clear driver new laert node on firebase
        //
        if (
            !in_array($this->order->status, ['pending']) &&
            $this->order->isDirty('driver_id') &&
            !empty($user) &&
            $this->user->hasRole('driver')
        ) {
            try {
                $driverNewOrderAlertRef = "driver_new_order/" . $this->user->id . "";
                $firestoreClient = $this->getFirebaseStoreClient();
                $firestoreClient->deleteDocument($driverNewOrderAlertRef);
            } catch (\Exception $ex) {
                logger("Error deleting driver new order alert firestore document", [$ex->getMessage() ?? $ex]);
            }
        }
    }
}
