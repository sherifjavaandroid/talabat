<?php

namespace App\Http\Livewire\Settings;


class CustomNotificationMessage extends BaseSettingsComponent
{

    //Custom notification messages
    public $pending;
    public $preparing;
    public $ready;
    public $enroute;
    public $completed;
    public $cancelled;
    public $failed;
    public $managerPreparingMssage;
    public $managerEnrouteMsg;



    public function mount()
    {
        $this->customNotificationSettings();
    }


    public function render()
    {
        return view('livewire.settings.custom_notification_message');
    }



    //custom notification messages
    public function customNotificationSettings()
    {
        $this->pending = setting("order.notification.message.pending", "");
        $this->preparing = setting("order.notification.message.preparing", "");
        $this->ready = setting("order.notification.message.ready", "");
        $this->enroute = setting("order.notification.message.enroute", "");
        $this->completed = setting("order.notification.message.completed", "");
        $this->cancelled = setting("order.notification.message.cancelled", "");
        $this->failed = setting("order.notification.message.failed", "");
        $this->managerPreparingMssage = setting("order.notification.message.manager.preparing", "");
        $this->managerEnrouteMsg = setting("order.notification.message.manager.enroute", "");
        $this->showCustomNotificationMessage = true;
    }

    public function saveCustomNotificationSettings()
    {

        try {

            $this->isDemo();
            setting([
                'order.notification.message.pending' =>  $this->pending,
                'order.notification.message.preparing' =>  $this->preparing,
                'order.notification.message.ready' =>  $this->ready,
                'order.notification.message.enroute' =>  $this->enroute,
                'order.notification.message.completed' =>  $this->completed,
                'order.notification.message.cancelled' =>  $this->cancelled,
                'order.notification.message.failed' =>  $this->failed,
                'order.notification.message.manager.preparing' =>  $this->managerPreparingMssage,
                'order.notification.message.manager.enroute' =>  $this->managerEnrouteMsg,
            ])->save();
            $this->showSuccessAlert(__("Custom Notification Message Setting saved successfully!"));
            $this->goback();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Custom Notification Message Setting Failed"));
        }
    }
}
