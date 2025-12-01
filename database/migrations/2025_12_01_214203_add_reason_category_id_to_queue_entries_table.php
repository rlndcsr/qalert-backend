<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('queue_entries', function (Blueprint $table) {
            // Add the column after `reason`
            $table->foreignId('reason_category_id')
                ->nullable() // allow null if old entries don’t have categories
                ->after('reason')
                ->constrained('reason_categories', 'reason_category_id')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('queue_entries', function (Blueprint $table) {
            // First drop the foreign key, then the column
            $table->dropForeign(['reason_category_id']);
            $table->dropColumn('reason_category_id');
        });
    }
};
