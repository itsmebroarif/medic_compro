<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inpatient_visits', function (Blueprint $table): void {
            $table->id();
            $table->string('admission_number', 50)->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->string('room_class', 50)->nullable();
            $table->string('room_number', 50)->nullable();
            $table->dateTime('admission_date');
            $table->dateTime('discharge_date')->nullable();
            $table->text('diagnosis')->nullable();
            $table->enum('status', ['admitted', 'discharged', 'cancelled'])->default('admitted');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('admission_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inpatient_visits');
    }
};
