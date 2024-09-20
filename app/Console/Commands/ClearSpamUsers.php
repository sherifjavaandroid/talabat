<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearSpamUsers extends Command
{
    //add force to the signature
    //Examlpe: php artisan spam:user:clear +964,+962,+233 --force
    protected $signature = 'spam:user:clear {dial_codes} {--force}';
    protected $description = 'Clear spam users based on the provided dial codes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        DB::beginTransaction();
        try {
            // Step 1: Get provided argument and explode it into an array
            $dialCodes = explode(',', $this->argument('dial_codes'));
            // logger("dialCodes", [$dialCodes]);

            // Step 2: Build the query to get all users not matching the allowed phone number start with values
            $query = DB::table('users');

            foreach ($dialCodes as $code) {
                //             WHERE phone NOT LIKE '+964%'
                //   AND phone NOT LIKE '+962%'
                //   AND phone NOT LIKE '+233%'
                $query->Where('phone', 'NOT LIKE', "$code%");
            }

            // Get all users matching the criteria
            $users = $query->get();

            // Step 3: Loop through the users and delete related records and the user account
            foreach ($users as $user) {
                // Find and delete related records in other tables
                $this->deleteRelatedRecords($user->id);

                // Delete the user account
                DB::table('users')->where('id', $user->id)->delete();

                $this->info("Deleted user with ID: {$user->id}");
            }

            $this->info('All spam users cleared.');
            //if force option is not provided, rollback the transaction
            if (!$this->option('force')) {
                DB::rollBack();
                $this->info('Transaction rolled back.');
            } else {
                DB::commit();
                $this->info('Transaction committed.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }

    private function deleteRelatedRecords($userId)
    {
        // Query to identify related tables and columns
        $relatedTables = DB::select("
            SELECT
                TABLE_NAME, COLUMN_NAME
            FROM
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE
                REFERENCED_TABLE_NAME = 'users'
                AND REFERENCED_COLUMN_NAME = 'id'
                AND TABLE_SCHEMA = DATABASE()
        ");

        //ignore foreign key constraints check
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Loop through each related table and delete records
        foreach ($relatedTables as $table) {
            DB::table($table->TABLE_NAME)->where($table->COLUMN_NAME, $userId)->delete();
            $this->info("Deleted related records in table: {$table->TABLE_NAME}");
        }
        //enable foreign key constraints check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}