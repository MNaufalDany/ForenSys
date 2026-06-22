<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autopsy_examinations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('case_id')
                ->unique()
                ->constrained('autopsy_cases')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('external_examination')->nullable();
            $table->longText('internal_examination')->nullable();
            $table->longText('findings')->nullable();
            $table->longText('cause_of_death')->nullable();
            $table->longText('notes')->nullable();

            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autopsy_examinations');
    }
};
