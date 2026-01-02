<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_schedule', function (Blueprint $table) {
            $table->id('doctor_schedule_id');

            $table->foreignId('schedule_id')
                ->constrained('schedules', 'schedule_id')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('doctors', 'doctor_id')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_schedule');
    }
};
