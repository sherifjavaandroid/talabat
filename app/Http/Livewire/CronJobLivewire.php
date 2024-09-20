<?php

namespace App\Http\Livewire;

use GeoSot\EnvEditor\Facades\EnvEditor;

class CronJobLivewire extends BaseLivewireComponent
{

    public $cronJobKey;


    public function render()
    {
        if (empty($this->cronJobKey)) {
            $this->cronJobKey = env("CRON_JOB_KEY", "");
        }
        return view('livewire.cron-job');
    }


    public function genNewKey()
    {

        $this->cronJobKey = \Str::random(32);
        if (EnvEditor::keyExists("CRON_JOB_KEY")) {
            EnvEditor::editKey("CRON_JOB_KEY", "'" . $this->cronJobKey . "'");
        } else {
            EnvEditor::addKey("CRON_JOB_KEY", "'" . $this->cronJobKey . "'");
        }
    }
}
