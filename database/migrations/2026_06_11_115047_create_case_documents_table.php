<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('case_id')
                ->constrained('autopsy_cases')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('document_name', 150);

            $table->enum('document_type', [
                'referral_letter',
                'identity',
                'photo',
                'other'
            ])->default('other');

            $table->string('file_path');

            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_documents');
    }
};
