<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CleanWrongRoleAssignment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-wrong-role-assignment {role?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean user account with specified role (default: client) + additional roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Retrieve the role name from the argument or default to 'client'
        $roleName = $this->argument('role') ?? 'client';

        // Fetch the role_id for the specified role
        $roleId = Role::where('name', $roleName)->value('id');

        if (!$roleId) {
            $this->error("Role '{$roleName}' not found.");
            return;
        }

        // Fetch model_ids that have the specified role
        $modelIdsWithSpecifiedRole = DB::table('model_has_roles')
            ->select('model_id')
            ->where('role_id', $roleId)
            ->pluck('model_id');

        // Fetch model_ids that have the specified role and at least one other role
        $modelIdsWithMultipleRoles = DB::table('model_has_roles')
            ->select('model_id')
            ->whereIn('model_id', $modelIdsWithSpecifiedRole)
            ->groupBy('model_id')
            ->havingRaw('COUNT(DISTINCT role_id) > 1')
            ->pluck('model_id');

        // Count the records to be deleted
        $recordsToDelete = DB::table('model_has_roles')
            ->whereIn('model_id', $modelIdsWithMultipleRoles)
            ->where('role_id', $roleId)
            ->count();

        if ($recordsToDelete === 0) {
            $this->info("No records found for role '{$roleName}' with additional roles.");
            return;
        }

        // Ask for confirmation
        if ($this->confirm("Found {$recordsToDelete} records with role '{$roleName}' to be deleted. Do you wish to continue?")) {
            // Delete rows with the specified role for the fetched model_ids
            DB::table('model_has_roles')
                ->whereIn('model_id', $modelIdsWithMultipleRoles)
                ->where('role_id', $roleId)
                ->delete();

            $this->info("Cleaned up role '{$roleName}' from users with multiple roles.");

            // Clear records where the model_id doesn't exist in users table
            $orphanedRecords = DB::table('model_has_roles')
                ->leftJoin('users', 'model_has_roles.model_id', '=', 'users.id')
                ->whereNull('users.id')
                ->delete();

            $this->info("Cleaned up {$orphanedRecords} orphaned role assignments where the user does not exist.");
        } else {
            $this->info('Operation cancelled.');
        }
    }
}