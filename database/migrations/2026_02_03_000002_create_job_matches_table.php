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
        Schema::create('job_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('placement_profile_id')->constrained()->onDelete('cascade');
            
            $table->string('job_title');
            $table->string('company_name');
            $table->enum('source', ['indeed', 'linkedin', 'glassdoor', 'workopolis', 'workday', 'wellfound'])->nullable();
            $table->text('job_description')->nullable();
            $table->json('required_skills')->nullable();
            $table->string('location')->nullable();
            $table->string('job_url');
            $table->string('image_url')->nullable();
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            
            // Match Score
            $table->integer('match_score')->nullable(); // 0-100
            $table->json('matched_skills')->nullable();
            $table->json('missing_skills')->nullable();
            
            // Posted timeline
            $table->string('posted_date')->nullable();
            $table->integer('days_posted')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_matches');
    }
};
