<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, check the current column type and values
        $columnType = DB::select("SHOW COLUMNS FROM rentals WHERE Field = 'status'")[0]->Type;
        
        // If it's an ENUM, we need to modify it to include 'rejected'
        if (str_contains($columnType, 'enum')) {
            // Extract the current enum values
            preg_match("/^enum\((.*)\)$/", $columnType, $matches);
            $values = str_getcsv($matches[1], ',', "'");
            
            // Add 'rejected' if it doesn't exist
            if (!in_array('rejected', $values)) {
                $values[] = 'rejected';
                
                // Create the new enum definition
                $enumString = "'" . implode("','", $values) . "'";
                
                // Alter the column
                DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM($enumString) NOT NULL DEFAULT 'pending'");
            }
        } else {
            // If it's not an ENUM, we can just modify it to be a string
            Schema::table('rentals', function (Blueprint $table) {
                $table->string('status')->change();
            });
        }
    }

    public function down(): void
    {
        // This is a bit tricky to revert since we don't know the original state
        // If you need to revert, you should specify the exact original enum values
    }
};