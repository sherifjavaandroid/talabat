<?php

namespace App\Upgrades;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class BaseUpgrade
{

    public $versionName = "";

    public function update()
    {
        //
        $versionCode = (int) setting('appVerisonCode', 1) + 1;

        setting([
            'appVerisonCode' =>  $versionCode,
            'appVerison' =>  $this->versionName,
        ])->save();
    }

    function fileEditContents($file_name, $line, $new_value)
    {
        $file = explode("\n", rtrim(file_get_contents($file_name)));
        $file[$line] = $new_value;
        $file = implode("\n", $file);
        file_put_contents($file_name, $file);
    }

    public function runMigration($path, $table, $force = true)
    {
        //has table 'user_firebase_tokens'
        $hasTable = Schema::hasTable($table);
        if (!$hasTable) {
            if ($force) {
                Artisan::call("migrate --path=$path --force");
            } else {
                Artisan::call("migrate --path=$path");
            }
        }
    }
}
