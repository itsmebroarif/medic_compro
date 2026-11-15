<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table): void {
            $table->id();
            $table->string('mr_number', 50)->unique();
            $table->string('nik', 16)->nullable();
            $table->string('name', 150);
            $table->date('dob');
            $table->enum('gender', ['L', 'P']);
            $table->string('phone', 25)->nullable();
            $table->text('address')->nullable();
            $table->text('allergies')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
