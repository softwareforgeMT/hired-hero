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
        Schema::create('cover_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_match_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('job_title');
            $table->string('company_name');
            $table->longText('content'); // The cover letter content
            $table->string('file_path')->nullable(); // Path to stored PDF
            $table->string('file_url')->nullable(); // Download URL
            $table->enum('status', ['draft', 'finalized', 'archived'])->default('draft');
            $table->timestamps();

            // Indexes for faster queries
            $table->index('user_id');
            $table->index('job_match_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cover_letters');
    }
};
