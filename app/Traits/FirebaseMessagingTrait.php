<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\User;
use App\Models\UserToken;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;

use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\WebPushConfig;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;

trait FirebaseMessagingTrait
{

    use FirebaseAuthTrait, OrderNotificationStatusMessageTrait, FirebaseNotificationValidateTrait;
    public $tempLocale;

    private function sendPlainFirebaseNotification(
        $topic,
        $title,
        $body,
        $image = null,
    ) {

        // igNore in local
        if (\App::environment('local')) {
            return;
        }

        //getting firebase messaging
        $messaging = $this->getFirebaseMessaging();
        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
            'image' => $image,
        ]);
        //
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification($notification) // optional
            ->withData($data ?? []); // optional

        $messaging->validate($message);
        $messaging->send($message);

        /*
        $messagePayload = [
            'topic' => (string) $topic,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'image' => $image,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
        ];

        $message = CloudMessage::fromArray($messagePayload);

        //android configuration
        $androidConfig = [
            'ttl' => '3600s',
            'priority' => 'high',
            'notification' => [
                'title' => $title,
                'body' => $body,
                'image' => $image,
            ],
        ];

        $apnConfig = ApnsConfig::fromArray([
            'headers' => [
                "apns-push-type" => "background",
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $body,
                        'image' => $image,
                    ],
                ],
            ],
        ]);



        $config = AndroidConfig::fromArray($androidConfig);
        $message = $message->withAndroidConfig($config);
        $message = $message->withApnsConfig($apnConfig);

        // logger("sendFirebaseNotification", [$messagePayload]);
        // logger("AndroidConfig", [$androidConfig]);
        // logger("ApnsConfig", [$apnConfig]);
        try {
            $messaging->validate($message);
            $messaging->send($message);
        } catch (InvalidMessage $e) {
            logger("InvalidMessage", [$e->getMessage(), $e]);
        }
        */
    }

    //
    private function sendFirebaseNotification(
        $topic,
        $title,
        $body,
        array $data = null,
        bool $onlyData = true,
        string $channel_id = "basic_channel",
        bool $noSound = false,
        String $image = null,
    ) {

        // igNore in local
        if (\App::environment('local')) {
            return;
        }

        //check if notification has been sent before
        if ($this->validateNotification($topic, $title, $body, $data, $onlyData, $channel_id, $noSound, $image)) {
            return;
        }

        //getting firebase messaging
        $messaging = $this->getFirebaseMessaging();
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification(Notification::fromArray([
                'title' => $title,
                'body' => $body,
                'image' => $image,
            ]));

        //send with data if provided and not null
        if ($data != null) {
            $message = $message->withData($data ?? []);
        }

        //add android config
        $androidConfig = AndroidConfig::fromArray([
            'priority' => 'high',
            'notification' => [
                'title' => $title,
                'body' => $body,
                'image' => $image,
                'sound' => $noSound ? "default" : "alert",
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'channel_id' => $channel_id,
            ],
        ]);
        $message = $message->withAndroidConfig($androidConfig);

        //add apns config regrding sound
        $apnConfig = ApnsConfig::fromArray([
            'headers' => [
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'sound' => $noSound ? "default" : "alert.aiff",
                ],
            ],
            'fcm_options' => [
                'analytics_label' => 'analytics',
                'image' => $image ?? '',
            ],
        ]);

        $message = $message->withApnsConfig($apnConfig);
        $messaging->validate($message);
        $messaging->send($message);


        /*
        $messagePayload = [
            'topic' => (string) $topic,
            // 'notification' => $onlyData ? null : [
            'notification' => [
                'title' => $title,
                'body' => $body,
                'image' => $image,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                "channel_id" => $channel_id,
                "sound" => $noSound ? "" : "alert.aiff",
            ],
            'data' => $data,
        ];

        if (!$onlyData) {
            $messagePayload = [
                'topic' => (string) $topic,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'image' => $image,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    "channel_id" => $channel_id,
                    "sound" => $noSound ? "" : "alert.aiff",
                ],
            ];
        } else {

            if (empty($data["title"])) {
                $data["title"] = $title;
                $data["body"] = $body;
            }
            $messagePayload = [
                'topic' => (string) $topic,
                'notification' => $messagePayload['notification'],
                'data' => $data,
            ];
        }
        $message = CloudMessage::fromArray($messagePayload);

        //android configuration
        $androidConfig = [
            'ttl' => '3600s',
            'priority' => 'high',
            'data' => $data,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'image' => $image,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                "channel_id" => $channel_id,
                "sound" => $noSound ? "" : "alert",
            ],
        ];

        $apnConfig = ApnsConfig::fromArray([
            'headers' => [
                "apns-push-type" => "background",
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $body,
                        'image' => $image,
                    ],
                    // 'badge' => 42,
                    "sound" => $noSound ? "default" : "alert",
                    "category" => "FLUTTER_NOTIFICATION_CLICK",
                ],
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
            ],
        ]);


        if ($onlyData) {
            if (empty($data["title"])) {
                $data["title"] = $title;
                $data["body"] = $body;
            }
            $androidConfig = [
                'ttl' => '3600s',
                'priority' => 'high',
                'data' => $data,
            ];
        }
        $config = AndroidConfig::fromArray($androidConfig);
        $message = $message->withAndroidConfig($config);
        $message = $message->withApnsConfig($apnConfig);

        // logger("sendFirebaseNotification", [$messagePayload]);
        // logger("AndroidConfig", [$androidConfig]);
        // logger("ApnsConfig", [$apnConfig]);
        try {
            $messaging->validate($message);
            $messaging->send($message);
        } catch (InvalidMessage $e) {
            logger("InvalidMessage", [$e->getMessage(), $e]);
        }
        */
    }

    private function sendOrderFirebaseNotification(
        $topic,
        $title,
        $body,
        array $data,
        $deviceTokens = null,
    ) {

        // igNore in local
        if (\App::environment('local')) {
            return;
        }

        //getting firebase messaging
        $messaging = $this->getFirebaseMessaging();
        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
        ]);
        //
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification($notification) // optional
            ->withData($data ?? []); // optional

        //if array to tokens is provided
        if ($deviceTokens != null && is_array($deviceTokens) && count($deviceTokens) > 0) {
            $messaging->sendMulticast($message, $deviceTokens);
        } else {
            $messaging->validate($message);
            $messaging->send($message);
        }

        /*
        $messagePayload = [
            'topic' => (string) $topic,
            'data' => $data,
        ];

        if (empty($data["title"])) {
            $data["title"] = $title;
            $data["body"] = $body;
        }
        $messagePayload = [
            'topic' => (string) $topic,
            'data' => $data,
        ];

        $message = CloudMessage::fromArray($messagePayload);
        //android configuration
        $androidConfig = [
            'ttl' => '3600s',
            'priority' => 'high',
            'data' => $data,
        ];

        $apnConfig = ApnsConfig::fromArray([
            'headers' => [
                "apns-push-type" => "background",
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    "content-available" => 1,
                ],
            ],
        ]);


        $config = AndroidConfig::fromArray($androidConfig);
        $message = $message->withAndroidConfig($config);
        $message = $message->withApnsConfig($apnConfig);

        try {
            //if array to tokens is provided
            if ($deviceTokens != null && is_array($deviceTokens) && count($deviceTokens) > 0) {
                $messaging->sendMulticast($message, $deviceTokens);
            } else {
                $messaging->validate($message);
                $messaging->send($message);
            }
        } catch (InvalidMessage $e) {
            logger("InvalidMessage", [$e->getMessage(), $e]);
        }
        */
    }

    private function sendFirebaseNotificationToTokens(array $tokens, $title, $body, array $data = null)
    {

        // igNore in local
        if (\App::environment('local')) {
            return;
        }

        //check if notification has been sent before
        if ($this->validateTokenNotification($tokens, $title, $body)) {
            return;
        }

        if (!empty($tokens)) {
            //getting firebase messaging
            $messaging = $this->getFirebaseMessaging();
            $message = CloudMessage::new();
            //
            $config = WebPushConfig::fromArray([
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'icon' => setting('websiteLogo', asset('images/logo.png')),
                ],
                'fcm_options' => [
                    'link' => $data[0],
                ],
            ]);
            //
            $message = $message->withWebPushConfig($config);
            $messaging->sendMulticast($message, $tokens);
        }
    }










    //
    public function sendOrderStatusChangeNotification(Order $order, $status = null)
    {

        try {
            // logger("sendOrderStatusChangeNotification called");
            // logger("order notification", [$order->code]);
            $this->loadLocale();
            //order data
            $orderData = [
                'is_order' => "1",
                'order_id' => (string)$order->id,
                'status' => $order->status,

            ];
            //for taxi orders
            if (!empty($order->taxi_order) || empty($order->vendor)) {
                // logger("order type as taxi", [$order->code]);
                $this->sendTaxiOrderStatusChangeNotification($order);
                return;
            }
            //
            $managersId = $order->vendor->managers->pluck('id')->all() ?? [];
            $managersTokens = UserToken::whereIn('user_id', $managersId)->pluck('token')->toArray();

            //notification message
            $notificationTitle = setting('websiteName', env("APP_NAME"));
            $customerNotificationMessage = $this->getCustomerOrderNotificationMessage(
                $status ?? $order->status,
                $order,
            );
            //customer
            $this->sendOrderFirebaseNotification(
                $order->user_id,
                $notificationTitle,
                $customerNotificationMessage,
                $orderData,
                //user tokens
                $order->user->notification_tokens ?? null,
            );
            //vendor
            if (!empty($order->vendor_id)) {
                // logger("send vendor notification", []);
                $vendorNotificationMessage = $this->getVendorOrderNotificationMessage(
                    $status ?? $order->status,
                    $order,
                );
                $vendorTopic = "v_" . $order->vendor_id . "";
                // logger("vendorTopic", [$vendorTopic]);
                $this->sendOrderFirebaseNotification(
                    $vendorTopic,
                    $notificationTitle,
                    $vendorNotificationMessage,
                    $orderData,
                    //vendor manager tokens
                    $managersTokens,
                );
                //vendor web
                $this->sendFirebaseNotificationToTokens(
                    $managersTokens,
                    $notificationTitle,
                    $vendorNotificationMessage,
                    [
                        "" . route('orders') . "?filters[search]=" . $order->code . ""
                    ],
                );
            }
            //driver
            if ($order->status == "delivered" && !empty($order->driver_id)) {
                $this->sendOrderFirebaseNotification(
                    $order->driver_id,
                    $notificationTitle,
                    $customerNotificationMessage,
                    $orderData,
                    //driver tokens
                    $order->driver->notification_tokens ?? null,
                );
            }


            // logger("About to send notification base order status",[
            //     "status" => $order->status
            // ]);
            //send notifications to admin & city-admin
            //admin
            if (setting("notifyAdmin", 0)) {
                //sending notification to admin accounts
                $adminsIds = User::admin()->pluck('id')->all();
                $adminTokens = UserToken::whereIn('user_id', $adminsIds)->pluck('token')->toArray();
                //
                $this->sendFirebaseNotificationToTokens(
                    $adminTokens,
                    __("Order Notification"),
                    __("Order #") . $order->code . " " . __("with") . " " . $order->vendor->name . " " . __("is now:") . " " . $order->status,
                    [
                        "" . route('orders') . "?filters[search]=" . $order->code . ""
                    ],
                );
            }
            //city-admin
            if (setting("notifyCityAdmin", 0) && !empty($order->vendor->creator_id)) {
                //sending notification to city-admin accounts
                $cityAdminTokens = UserToken::where('user_id', $order->vendor->creator_id)->pluck('token')->toArray();
                //
                $this->sendFirebaseNotificationToTokens(
                    $cityAdminTokens,
                    __("Order Notification"),
                    __("Order #") . $order->code . " " . __("with") . " " . $order->vendor->name . " " . __("is now:") . " " . $order->status,
                    [
                        "" . route('orders') . "?filters[search]=" . $order->code . ""
                    ],
                );
            }
            $this->resetLocale();
        } catch (\Exception $e) {
            logger("sendOrderStatusChangeNotification error", [$e->getMessage(), $e]);
        }
    }

    //
    public function sendTaxiOrderStatusChangeNotification(Order $order)
    {

        $this->loadLocale();
        //order data
        $orderData = [
            'is_order' => "0",
            'order_id' => (string)$order->id,
            'status' => $order->status,

        ];

        $pendingMsg = setting('taxi.msg.pending', __("Searching for driver"));
        $preparingMsg = setting('taxi.msg.preparing', __("Driver assigned to your trip and their way"));
        $readyMsg = setting('taxi.msg.ready', __("Driver has arrived"));
        $enrouteMsg = setting('taxi.msg.enroute', __("Trip started"));
        $completedMsg = setting('taxi.msg.completed', __("Trip completed"));
        $cancelledMsg = setting('taxi.msg.cancelled', __("Trip was cancelled"));
        $failedMsg = setting('taxi.msg.failed', __("Trip failed"));
        $notificationTitle = setting('websiteName', env("APP_NAME"));

        //'pending','preparing','ready','enroute','delivered','failed','cancelled'
        if ($order->status == "pending") {
            $this->sendOrderFirebaseNotification($order->user_id, $notificationTitle, $pendingMsg, $orderData);
        } else if ($order->status == "preparing") {
            $this->sendOrderFirebaseNotification($order->user_id, $notificationTitle, $preparingMsg, $orderData);
        } else if ($order->status == "ready") {
            $this->sendOrderFirebaseNotification($order->user_id, $notificationTitle, $readyMsg, $orderData);
        } else if ($order->status == "enroute") {

            //user
            $this->sendOrderFirebaseNotification($order->user_id, $notificationTitle, $enrouteMsg, $orderData);
        } else if ($order->status == "delivered") {


            //user/customer
            $this->sendOrderFirebaseNotification(
                $order->user_id,
                $notificationTitle,
                $completedMsg,
                $orderData,
            );

            //user/customer overdraft
            $hasOverdraft = $order->has_over_draft;
            if ($hasOverdraft) {
                /**
                 * :amt - total
                 * :bal - outstanding
                 * :pai - already paid
                 */
                $amt = currencyFormat($order->outstanding_balance->amount ?? "");
                $bal = currencyFormat($order->outstanding_balance->balance ?? "");
                $pai = currencyFormat($order->outstanding_balance->paid ?? "");
                //
                if ($order->payment_method->slug == "cash") {
                    $customerOverDraftMsg = setting('taxi.msg.cash_overdraft_completed', (__("Pay driver") . ":amt"));
                } else {
                    $message = __("Trip total") . " :amt," . __("but you have paid") . " :pai ";
                    $message .= __("the balance of") . " :bal " . __("will be deduted from your account wallet");
                    $customerOverDraftMsg = setting('taxi.msg.overdraft_completed', $message);
                }
                //replce the values
                $customerOverDraftMsg = str_replace(":amt", $amt, $customerOverDraftMsg);
                $customerOverDraftMsg = str_replace(":bal", $bal, $customerOverDraftMsg);
                $customerOverDraftMsg = str_replace(":pai", $pai, $customerOverDraftMsg);
                //
                $this->sendOrderFirebaseNotification(
                    $order->user_id,
                    $notificationTitle,
                    $customerOverDraftMsg,
                    $orderData,
                );
            }

            //driver
            if (!empty($order->driver_id)) {
                $this->sendOrderFirebaseNotification(
                    $order->driver_id,
                    $notificationTitle,
                    $completedMsg,
                    $orderData
                );
            }
        } else if ($order->status == "failed") {
            $this->sendOrderFirebaseNotification($order->user_id, $notificationTitle, $failedMsg, $orderData);
        } else if ($order->status == "cancelled") {
            $this->sendOrderFirebaseNotification($order->user_id, $notificationTitle, $cancelledMsg, $orderData);
        } else if (!empty($order->status)) {
            $this->sendOrderFirebaseNotification($order->user_id, $notificationTitle, __("Trip #") . $order->code . __(" has been ") . __($order->status) . "", $orderData);
        }


        //send notifications to admin & city-admin
        //admin
        if (setting("notifyAdmin", 0)) {
            //sending notification to admin accounts
            $adminsIds = User::admin()->pluck('id')->all();
            $adminTokens = UserToken::whereIn('user_id', $adminsIds)->pluck('token')->toArray();
            //
            $this->sendFirebaseNotificationToTokens(
                $adminTokens,
                __("Trip Notification"),
                __("Trip #") . $order->code . " " . __("by") . " " . $order->user->name . " " . __("is now:") . " " . $order->status,
                [route('orders')]
            );
        }
        $this->resetLocale();
    }


    public function sendOrderNotificationToDriver(Order $order)
    {


        //order data
        $orderData = [
            'is_order' => "1",
            'order_id' => (string)$order->id,
            'status' => $order->status,

        ];

        //aviod send order details notification data when order is taxi
        if (!empty($order->taxi_order)) {
            $orderData["is_order"] = "0";
        }

        //
        $this->loadLocale();
        $this->sendOrderFirebaseNotification(
            $order->driver_id,
            __("Order Update"),
            __("Order #") . $order->code . __(" has been assigned to you"),
            $orderData
        );
        $this->resetLocale();
    }



    //LOCALE CONFIG
    public function loadLocale()
    {
        $this->tempLocale = setting('localeCode', 'en');
        \App::setLocale($this->tempLocale);
    }
    public function resetLocale()
    {
        \App::setLocale($this->tempLocale);
    }
}
