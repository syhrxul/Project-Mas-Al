<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('rentals', 'quantity')) {
            Schema::table('rentals', function (Blueprint $table) {
                $table->integer('quantity')->default(1)->after('end_datetime');
            });
        }
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};