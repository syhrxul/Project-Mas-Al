<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateUserRole extends Command
{
    protected $signature = 'user:role {user_id} {role}';
    
    protected $description = 'Update a user\'s role';
    
    public function handle()
    {
        $userId = $this->argument('user_id');
        $role = $this->argument('role');
        
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }
        
        // Display current user information
        $this->info("Current user information:");
        $this->line("ID: {$user->id}");
        $this->line("Name: {$user->name}");
        $this->line("Email: {$user->email}");
        $this->line("Current role in users table: {$user->role}");
        
        // Check role_user table if it exists
        if (Schema::hasTable('role_user')) {
            $currentRoleId = DB::table('role_user')->where('user_id', $userId)->value('role_id');
            $currentRoleName = null;
            
            if ($currentRoleId) {
                $currentRoleName = DB::table('roles')->where('id', $currentRoleId)->value('name');
                $this->line("Current role in role_user table: {$currentRoleName} (ID: {$currentRoleId})");
            } else {
                $this->line("No role assignment found in role_user table");
            }
        }
        
        // Update the role in users table
        $user->update(['role' => $role]);
        $this->info("Updated user {$user->name} role to {$role} in users table");
        
        // Check if role_user table exists
        if (Schema::hasTable('role_user') && Schema::hasTable('roles')) {
            // Show all available roles
            $this->info("Available roles in roles table:");
            $roles = DB::table('roles')->get();
            foreach ($roles as $r) {
                $this->line("- ID: {$r->id}, Name: {$r->name}");
            }
            
            // Find the role ID
            $roleId = DB::table('roles')->where('name', $role)->value('id');
            
            if (!$roleId) {
                $this->warn("Role '{$role}' not found in roles table. Trying case-insensitive search...");
                
                // Try case-insensitive search
                $roleRecord = DB::table('roles')
                    ->whereRaw('LOWER(name) = ?', [strtolower($role)])
                    ->first();
                
                if ($roleRecord) {
                    $roleId = $roleRecord->id;
                    $this->info("Found role with case-insensitive search: {$roleRecord->name} (ID: {$roleId})");
                }
            }
            
            if ($roleId) {
                // Delete existing role assignments
                DB::table('role_user')->where('user_id', $userId)->delete();
                
                // Insert new role assignment
                DB::table('role_user')->insert([
                    'user_id' => $userId,
                    'role_id' => $roleId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->info("Updated user role in role_user table to role_id: {$roleId}");
            } else {
                $this->error("Role '{$role}' not found in roles table");
            }
        }
        
        return 0;
    }
}