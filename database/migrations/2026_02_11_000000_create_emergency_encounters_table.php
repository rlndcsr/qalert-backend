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
        Schema::create('emergency_encounters', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('id_number', 50)->nullable();
            $table->string('contact_number');
            $table->date('date');
            $table->time('time');
            $table->text('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_encounters');
    }
};
