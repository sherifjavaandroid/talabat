<?php

namespace App\Http\Livewire;

use App\Mail\PlainMail;
use App\Services\MailHandlerService;
use Exception;
use GeoSot\EnvEditor\Facades\EnvEditor;

class ServerSettingsLivewire extends BaseLivewireComponent
{

    // App settings
    public $mailHost;
    public $mailPort;
    public $mailUsername;
    public $mailPassword;
    public $mailFromAddress;
    public $mailFromName;
    public $mailEncryption;
    public $systemEmail;

    //testing
    public $testEmail;
    public $testSubject;
    public $testBody;


    public function mount()
    {
        //
        $this->mailHost = env('MAIL_HOST');
        $this->mailPort = env('MAIL_PORT');
        $this->mailUsername = env('MAIL_USERNAME');
        // $this->mailPassword = env('MAIL_PASSWORD');
        $this->mailFromAddress = env('MAIL_FROM_ADDRESS');
        $this->mailFromName = env('MAIL_FROM_NAME');
        $this->mailEncryption = env('MAIL_ENCRYPTION', 'tls');
        $this->systemEmail = env('SYSTEM_EMAIL', '');
    }

    public function render()
    {
        $this->mount();
        return view('livewire.settings.server-settings');
    }


    public function saveMailSettings()
    {

        $this->validate([
            "mailHost" => "required",
            "mailPort" => "required",
            "mailUsername" => "required",
            "mailPassword" => "sometimes",
            'mailFromAddress' => "required",
            'mailFromName' => "required",
            'mailEncryption' => "required",
        ]);

        try {

            $this->isDemo();
            EnvEditor::editKey("MAIL_HOST", $this->mailHost);
            EnvEditor::editKey("MAIL_PORT", $this->mailPort);
            EnvEditor::editKey("MAIL_USERNAME", "'" . $this->mailUsername . "'");
            if (!empty($this->mailPassword)) {
                EnvEditor::editKey("MAIL_PASSWORD", "'" . $this->mailPassword . "'");
            }
            EnvEditor::editKey("MAIL_ENCRYPTION", "'" . $this->mailEncryption . "'");
            if (EnvEditor::keyExists("MAIL_FROM_ADDRESS")) {
                EnvEditor::editKey("MAIL_FROM_ADDRESS", "'" . $this->mailFromAddress . "'");
            } else {
                EnvEditor::addKey("MAIL_FROM_ADDRESS", "'" . $this->mailFromAddress . "'", ['group' => 'MAIL']);
            }

            if (EnvEditor::keyExists("MAIL_FROM_NAME")) {
                EnvEditor::editKey("MAIL_FROM_NAME", "'" . $this->mailFromName . "'");
            } else {
                EnvEditor::addKey("MAIL_FROM_NAME", "'" . $this->mailFromName . "'", ['group' => 'MAIL']);
            }


            $this->showSuccessAlert(__("Mail Settings saved successfully!"));
            $this->reset();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Mail Settings save failed!"));
        }
    }


    public function saveSystemMailSettings()
    {

        $this->validate([
            "systemEmail" => "required|email",
        ]);

        try {

            $this->isDemo();
            if (EnvEditor::keyExists("SYSTEM_EMAIL")) {
                EnvEditor::editKey("SYSTEM_EMAIL", $this->systemEmail);
            } else {
                EnvEditor::addKey("SYSTEM_EMAIL", $this->systemEmail);
            }
            $this->showSuccessAlert(__("System Mail saved successfully!"));
            $this->reset();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("System email failed!"));
        }
    }

    public function testMailSettings()
    {

        $this->validate([
            "testEmail" => "required",
            "testSubject" => "required",
            "testBody" => "required",
        ]);

        try {

            // \Mail::to($this->testEmail)->send(new PlainMail($this->testSubject, $this->testBody));
            MailHandlerService::sendMail(new PlainMail($this->testSubject, $this->testBody), $this->testEmail);
            $this->showSuccessAlert(__("Test email sent successfully!"));
            $this->reset();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __("Test email failed!"));
        }
    }
}
