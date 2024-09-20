<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetAdminAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:account:admin {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset admin account details';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //
        if (\App::runningInConsole()) {
            $email = $this->argument('email');
            $password = $this->argument('password');
            $adminAccount = User::where('email', $email)->first();
            if (empty($adminAccount)) {
                $this->error('No admin account found with provided email');
            } else if (!$adminAccount->hasRole('admin')) {
                $this->warn('Account is not an admin account. Please provide email for an admin account');
            } else {
                //
                try {

                    DB::beginTransaction();
                    $adminAccount->password = Hash::make($password);
                    $adminAccount->save();
                    DB::commit();
                    $this->info('Account password updated successfully');
                } catch (\Exception $error) {
                    DB::rollback();
                    $this->error($error->getMessage() ?? "Account password update failed");
                }
            }
        } else {
            $this->error('Command needs to be runned in console/terminal');
        }
        return 0;
    }
}
