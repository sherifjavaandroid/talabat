<?php

namespace App\Traits;

use App\Models\FirebaseNotification;

trait FirebaseNotificationValidateTrait
{

    public function validateTokenNotification(array $tokens, $title, $body, array $data = null)
    {

        $this->prepTable();
        //create encrypted token from provided values
        $encryptedToken = encrypt(json_encode($tokens) . "-" . $title . "-" . $body . "-" . json_encode($data));
        //fetch from table to check if token already exists
        $fcmNotification = FirebaseNotification::where('encrypted_token', $encryptedToken)->first();
        if ($fcmNotification == null) {
            //create new record
            $fcmNotification = new FirebaseNotification();
            $fcmNotification->encrypted_token = $encryptedToken;
            $fcmNotification->topic = json_encode($tokens);
            $fcmNotification->save();
            return false;
        } else {
            return true;
        }
    }

    public function validateNotification(
        $topic,
        $title,
        $body,
        array $data = null,
        bool $onlyData = true,
        string $channel_id = "basic_channel",
        bool $noSound = false,
        String $image = null
    ) {

        $this->prepTable();
        //create encrypted token from provided values
        $encryptedToken = encrypt($topic . "-" . $title . "-" . $body . "-" . json_encode($data) . "-" . $onlyData . "-" . $channel_id . "-" . $noSound . "-" . $image);
        //fetch from table to check if token already exists
        $fcmNotification = FirebaseNotification::where('encrypted_token', $encryptedToken)->first();
        if ($fcmNotification == null) {
            //create new record
            $fcmNotification = new FirebaseNotification();
            $fcmNotification->encrypted_token = $encryptedToken;
            $fcmNotification->topic = $topic;
            $fcmNotification->save();
            return false;
        } else {
            return true;
        }
    }


    ///
    public function prepTable()
    {
        if (!\Schema::hasTable('firebase_notifications')) {
            \Artisan::call('migrate --path=database/migrations/2023_06_14_195135_create_firebase_notifications_table.php --force');
        }
    }
}
