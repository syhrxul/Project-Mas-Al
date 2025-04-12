<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckRoles extends Command
{
    protected $signature = 'roles:check';
    
    protected $description = 'Check roles in the database';
    
    public function handle()
    {
        // Check roles table
        if (Schema::hasTable('roles')) {
            $this->info("Roles in roles table:");
            $roles = DB::table('roles')->get();
            
            if ($roles->isEmpty()) {
                $this->warn("No roles found in roles table");
            } else {
                foreach ($roles as $role) {
                    $this->line("- ID: {$role->id}, Name: {$role->name}");
                }
            }
        } else {
            $this->warn("roles table does not exist");
        }
        
        // Check users with roles
        $this->info("\nUsers with roles:");
        $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
        
        foreach ($users as $user) {
            $this->line("User ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}");
            
            // Check role_user table if it exists
            if (Schema::hasTable('role_user')) {
                $roleAssignments = DB::table('role_user')
                    ->where('user_id', $user->id)
                    ->join('roles', 'role_user.role_id', '=', 'roles.id')
                    ->select('roles.id as role_id', 'roles.name as role_name')
                    ->get();
                
                if ($roleAssignments->isEmpty()) {
                    $this->warn("  No role assignments in role_user table");
                } else {
                    foreach ($roleAssignments as $assignment) {
                        $this->info("  Role in role_user: {$assignment->role_name} (ID: {$assignment->role_id})");
                    }
                }
            }
        }
        
        return 0;
    }
}