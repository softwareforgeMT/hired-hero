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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_match_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->string('job_title');
            $table->string('company_name');
            $table->string('job_url')->nullable();
            
            // Application Pipeline Stages
            $table->enum('status', [
                'to-review',
                'ready',
                'applied',
                'callback',
                'interview',
                'offer',
                'hired',
                'rejected',
                'archived'
            ])->default('to-review');
            
            // Application Tracking
            $table->timestamp('applied_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->integer('days_since_application')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            
            // Interview Tracking
            $table->timestamp('interview_date')->nullable();
            $table->text('interview_notes')->nullable();
            
            // Cover Letter
            $table->text('cover_letter')->nullable();
            $table->boolean('used_ai_cover_letter')->default(false);
            
            // Offer Details
            $table->decimal('offer_salary', 10, 2)->nullable();
            $table->timestamp('offer_date')->nullable();
            $table->boolean('offer_accepted')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
