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
        Schema::create('study_material_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained('study_material_folders')->onDelete('cascade');
            $table->string('title');
            $table->string('file_url', 2048);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_material_documents');
    }
};
