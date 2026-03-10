<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('emergency_encounters', function (Blueprint $table) {
            $table->enum('status', ['active', 'done', 'cancelled'])->default('active')->after('details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergency_encounters', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
