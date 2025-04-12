<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, let's check what roles we have in the roles table
        $roles = DB::table('roles')->select('id', 'name')->get();
        
        // Log the roles for debugging
        foreach ($roles as $role) {
            DB::statement("INSERT INTO migrations (migration, batch) VALUES ('Debug: Role ID {$role->id} has name {$role->name}', 0)");
        }
        
        // Get all users with their role assignments from role_user
        $userRoles = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('role_user.user_id', 'roles.id as role_id', 'roles.name as role_name')
            ->get();
        
        // Update each user's role in the users table
        foreach ($userRoles as $userRole) {
            DB::table('users')
                ->where('id', $userRole->user_id)
                ->update(['role' => $userRole->role_name]);
            
            // Log the update for debugging
            DB::statement("INSERT INTO migrations (migration, batch) VALUES ('Debug: Updated user ID {$userRole->user_id} to role {$userRole->role_name}', 0)");
        }
        
        // Specifically update admin users (role_id = 1 is typically admin)
        $adminRoleId = DB::table('roles')->where('name', 'like', '%admin%')->value('id');
        if ($adminRoleId) {
            $adminUserIds = DB::table('role_user')
                ->where('role_id', $adminRoleId)
                ->pluck('user_id');
            
            foreach ($adminUserIds as $adminUserId) {
                DB::table('users')
                    ->where('id', $adminUserId)
                    ->update(['role' => 'admin']);
                
                // Log the update for debugging
                DB::statement("INSERT INTO migrations (migration, batch) VALUES ('Debug: Updated admin user ID {$adminUserId} to role admin', 0)");
            }
        }
    }

    public function down(): void
    {
        // Reset all users to default 'user' role
        DB::table('users')
            ->update(['role' => 'user']);
    }
};