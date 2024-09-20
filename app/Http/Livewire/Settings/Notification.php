<?php

namespace App\Http\Livewire\Settings;


class Notification extends BaseSettingsComponent
{

    //firebase
    public $apiKey;
    public $resourceLocation;
    public $projectId;
    public $messagingSenderId;
    public $appId;
    public $vapidKey;
    public $notifyAdmin;
    public $notifyCityAdmin;
    public $useFCMJob;
    public $delayFCMJobSeconds;



    public function mount()
    {
        $this->notificationSetting();
    }


    public function render()
    {
        return view('livewire.settings.notification');
    }

    //Notification settings
    public function notificationSetting()
    {
        $this->apiKey = "XXXXXXXXXXXX";
        $this->projectId = setting('projectId', "");
        $this->resourceLocation = setting('resourceLocation', "us-central1");
        $this->messagingSenderId = setting('messagingSenderId', "");
        $this->appId = setting('appId', "");
        $this->vapidKey = "XXXXXXXXXXXX";
        $this->notifyAdmin = (bool) setting('notifyAdmin', "0");
        $this->notifyCityAdmin = (bool) setting('notifyCityAdmin', "0");
        $this->useFCMJob = (bool) setting('useFCMJob', "0");
        $this->delayFCMJobSeconds = (int) setting('delayFCMJobSeconds', 10);
    }

    public function saveNotificationSetting()
    {

        try {

            $this->isDemo();

            setting([
                "apiKey" => ($this->apiKey == "XXXXXXXXXXXX") ? setting('apiKey', 'XXXXXXXXXXXX') : $this->apiKey,
                "projectId" => $this->projectId,
                "resourceLocation" => $this->resourceLocation,
                "messagingSenderId" => $this->messagingSenderId,
                "appId" => $this->appId,
                "vapidKey" => ($this->vapidKey == "XXXXXXXXXXXX") ? setting('vapidKey', 'XXXXXXXXXXXX') : $this->vapidKey,
                "notifyAdmin" => $this->notifyAdmin ?? 0,
                "notifyCityAdmin" => $this->notifyCityAdmin ?? 0,
                "useFCMJob" => $this->useFCMJob ?? 0,
                "delayFCMJobSeconds" => $this->delayFCMJobSeconds ?? 10,
            ])->save();

            //change firenase service worker js file
            $file_name = base_path() . "/public/firebase-messaging-sw.js";
            $this->fileEditContents($file_name, "11", "messagingSenderId: '" . $this->messagingSenderId . "',");

            if ($this->photo != null) {

                $this->validate([
                    "photo" => "required|mimes:json",
                ]);




                //
                $serviceKeyPath = $this->photo->storeAs('vault', 'firebase_service.json');

                setting([
                    'serviceKeyPath' =>  $serviceKeyPath ?? "",
                ])->save();
            }

            $this->showSuccessAlert(__("Settings saved successfully!"));
            $this->goback();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Settings save failed!"));
        }
    }
}
