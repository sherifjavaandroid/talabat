<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Traits\FirebaseAuthTrait;
use App\Traits\FirebaseDBTrait;
use MrShan0\PHPFirestore\FirestoreClient;
use Exception;

class OrderChatLivewire extends BaseLivewireComponent
{

    use FirebaseAuthTrait;

    public $orderCode;
    public $chatTypes = [
        [
            "name" => "Customer - Vendor",
            "code" => "customerVendor",
        ],
        [
            "name" => "Customer - Driver",
            "code" => "customerDriver",
        ],
        [
            "name" => "Driver - Vendor",
            "code" => "driverVendor",
        ],
    ];
    public $selectedChatType;
    private $firestoreClient;
    public $chats = [];
    public $queryPage;
    public $order;



    public function mount($code)
    {
        $this->orderCode = $code;
        $this->order = Order::whereCode($code)->first();;
    }


    public function render()
    {
        return view('livewire.order_chat');
    }

    public function changeChatType($type)
    {
        $this->selectedChatType = $type;
        $this->fetchChats(true);
    }

    public function fetchChats($initial = false)
    {


        //
        try {

            // $this->isDemo();
        } catch (\Exception $error) {
            $this->showErrorAlert($error->getMessage());
            return;
        }

        try {


            //
            if ($this->firestoreClient == null) {
                $this->firestoreClient = $this->getFirebaseStoreClient();
            }
            //
            $orderRef = "orders/" . $this->orderCode . "/" . $this->selectedChatType . '/chats/Activity';
            $chatsDocs = $this->firestoreClient->listDocuments($orderRef, [
                "orderBy" => "timestamp",
            ]);
            if ($initial) {
                $this->emit('clearChats');
            }
            //
            $chats = [];
            foreach ($chatsDocs['documents'] as $key => $chatsDoc) {
                $chat =  $chatsDoc->toArray();
                $chatPhoto = $chatsDoc->get('photos');
                $photos = [];
                foreach ($chatPhoto as $key => $photo) {
                    $photoData = $photo->getData();
                    $photos[] = [
                        "url" => $photoData[0]['url'],
                    ];
                }
                $chat['photos'] = $photos;
                $chats[] = $chat;
            }
            if ($this->selectedChatType == "driverVendor") {
                $this->emit('loadChats', [$this->order->driver_id, $chats]);
            } else {
                $this->emit('loadChats', [$this->order->user_id, $chats]);
            }
        } catch (\Exception $error) {
            logger("Reading Docus error", [$error]);
            $this->showErrorAlert($error->getMessage());
        }
    }
}
