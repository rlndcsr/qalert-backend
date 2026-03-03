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
        Schema::table('queue_entries', function (Blueprint $table) {
            $table->timestamp('session_start_at')->nullable()->after('queue_status');
            $table->timestamp('session_end_at')->nullable()->after('session_start_at');
            $table->unsignedInteger('session_duration_minutes')->nullable()->after('session_end_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('queue_entries', function (Blueprint $table) {
            $table->dropColumn(['session_start_at', 'session_end_at', 'session_duration_minutes']);
        });
    }
};
