<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_status_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('case_id')
                ->constrained('autopsy_cases')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);

            $table->foreignId('changed_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_status_logs');
    }
};
