<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update the admin user based on email
        DB::table('users')
            ->where('email', 'admin@example.com')
            ->update(['role' => 'admin']);
    }

    public function down(): void
    {
        // Revert the admin user role back to user
        DB::table('users')
            ->where('email', 'admin@example.com')
            ->update(['role' => 'user']);
    }
};