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
        Schema::create('study_material_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('faculty_id');
            $table->string('branch_id');
            $table->unsignedTinyInteger('year');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['faculty_id', 'branch_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_material_folders');
    }
};
