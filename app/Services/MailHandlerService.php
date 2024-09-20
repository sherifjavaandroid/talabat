<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class MailHandlerService
{

    public static function sendMail($mailable, $email, $ccs = [])
    {

        //disable mail unless in production
        if (env('APP_ENV') != 'production') {
            return;
        }

        try {
            $mail = Mail::to($email);

            try {
                if (!empty($ccs)) {
                    $mail = $mail->cc($ccs);
                }
            } catch (\Exception $ex) {
            }

            try {
                $adminEmails = explode(",", env('SYSTEM_EMAIL', ''));
                if (!empty($adminEmails)) {
                    $mail = $mail->bcc($adminEmails);
                }
            } catch (\Exception $ex) {
                //
            }

            //
            if (!delayFCMJob()) {
                $mail->send($mailable);
            } else {
                $mail->queue($mailable);
            }
        } catch (\Exception $ex) {
            \Log::error($ex);
        }
    }
}
