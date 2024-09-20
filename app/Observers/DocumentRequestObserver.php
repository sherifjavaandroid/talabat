<?php

namespace App\Observers;

use App\Mail\DocumentRequestMail;
use App\Models\DocumentRequest;
use App\Services\AppLangService;
use App\Services\MailHandlerService;


class DocumentRequestObserver
{


    public function created(DocumentRequest $model)
    {
        AppLangService::tempLocale();
        $this->sendMail($model);
        AppLangService::restoreLocale();
    }

    public function updated(DocumentRequest $model)
    {
        AppLangService::tempLocale();
        $this->sendMail($model);
        AppLangService::restoreLocale();
    }


    //
    public function sendMail(DocumentRequest $documentRequest)
    {

        try {
            $email = $documentRequest->model->email;
            MailHandlerService::sendMail(
                new DocumentRequestMail($documentRequest),
                $email,
            );
        } catch (\Exception $ex) {
            // logger("Mail Error", [$ex]);
            logger("Mail Error");
        }
    }
}
