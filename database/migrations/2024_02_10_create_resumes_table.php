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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('placement_profile_id')->constrained()->cascadeOnDelete();
            $table->string('template_name'); // e.g., 'modern', 'classic', 'minimalist', 'professional', 'creative'
            $table->string('title')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_url')->nullable();
            $table->json('data')->nullable(); // Store structured resume data
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('placement_profile_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
