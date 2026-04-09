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
        Schema::create('placement_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Entry Page (Page 1)
            $table->enum('job_type', ['hybrid', 'in-person', 'remote', 'no-preference'])->nullable();
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            
            // Location Page (Page 2)
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->enum('work_permit_status', ['yes', 'no-sponsorship', 'no-remote-only'])->nullable();
            
            // Professional Profile (Page 3)
            $table->json('industries')->nullable();
            $table->enum('job_level', ['entry', 'mid', 'senior', 'executive', 'no-preference'])->nullable();
            
            // Resume Gate (Page 4)
            $table->string('resume_path')->nullable();
            $table->json('resume_data')->nullable(); // Parsed resume data
            $table->boolean('has_resume')->default(false);
            
            // Extracted Resume Data
            $table->json('skills')->nullable();
            $table->integer('years_experience')->nullable();
            $table->json('past_companies')->nullable();
            $table->json('past_sectors')->nullable();
            
            // AI Role Mapping (Page 6)
            $table->json('suggested_roles')->nullable();
            $table->json('selected_roles')->nullable(); // User-adjusted roles
            
            // Standardized Profile
            $table->json('standardized_profile')->nullable();
            
            // Workflow Tracking
            $table->integer('current_step')->default(1);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->date('free_access_expires_at')->nullable();
            
            // Subscription Status
            $table->boolean('has_active_placement_subscription')->default(false);
            $table->timestamp('placement_subscription_expires_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_profiles');
    }
};
