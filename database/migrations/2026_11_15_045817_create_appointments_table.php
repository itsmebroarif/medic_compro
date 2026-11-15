<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table): void {
            $table->id();
            $table->string('booking_code', 50)->unique();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->string('patient_name', 150);
            $table->string('patient_phone', 25);
            $table->date('patient_dob');
            $table->enum('patient_gender', ['L', 'P']);
            $table->string('mr_number', 50)->nullable();
            $table->date('appointment_date');
            $table->text('complaint')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'rescheduled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('appointment_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
