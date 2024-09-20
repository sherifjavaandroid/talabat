<?php

namespace App\Services;

use anlutro\LaravelSettings\DatabaseSettingStore as Base;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Connection;

class CustomDatabaseSettingStore extends Base
{
    public function __construct(Connection $connection)
    {
        parent::__construct(
            $connection,
            config('settings.table'),
            config('settings.keyColumn'),
            config('settings.valueColumn')
        );
    }

    protected function write(array $data)
    {
        parent::write($data);
        // restart queue workers to force them to read new settings.
        Artisan::call('queue:restart');
    }
}