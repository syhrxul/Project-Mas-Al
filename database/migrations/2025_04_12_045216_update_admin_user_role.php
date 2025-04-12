<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Sync all users' roles from role_user table to users table
        if (Schema::hasTable('role_user') && Schema::hasTable('roles')) {
            // Get all role assignments
            $roleAssignments = DB::table('role_user')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->select('role_user.user_id', 'roles.name as role_name')
                ->get();
            
            // Update each user's role in the users table
            foreach ($roleAssignments as $assignment) {
                DB::table('users')
                    ->where('id', $assignment->user_id)
                    ->update(['role' => $assignment->role_name]);
            }
        }
        
        // Ensure admin user has Administrator role
        DB::table('users')
            ->where('email', 'admin@example.com')
            ->update(['role' => 'Administrator']);
    }

    public function down(): void
    {
        // Reset all users to default 'User' role
        DB::table('users')
            ->update(['role' => 'User']);
            
        // Then re-sync from role_user table
        if (Schema::hasTable('role_user') && Schema::hasTable('roles')) {
            $roleAssignments = DB::table('role_user')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->select('role_user.user_id', 'roles.name as role_name')
                ->get();
            
            foreach ($roleAssignments as $assignment) {
                DB::table('users')
                    ->where('id', $assignment->user_id)
                    ->update(['role' => $assignment->role_name]);
            }
        }
    }
};