<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autopsy_cases', function (Blueprint $table) {
            $table->id();

            $table->string('case_number', 50)->unique();
            $table->string('victim_name', 100);
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->unsignedInteger('age')->nullable();
            $table->date('date_received');

            $table->enum('case_type', [
                'criminal',
                'accident',
                'unknown_death',
                'other'
            ])->default('other');

            $table->text('description')->nullable();

            $table->enum('status', [
                'registered',
                'assigned',
                'in_progress',
                'completed',
                'verified',
                'closed'
            ])->default('registered');

            $table->foreignId('registration_officer_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('assigned_doctor_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autopsy_cases');
    }
};
