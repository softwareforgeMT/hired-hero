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
        Schema::create('placement_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('placement_profile_id')->constrained()->onDelete('cascade');
            
            $table->integer('days_after_completion'); // 14, 30, 60, etc.
            $table->enum('survey_type', ['initial-progress', 'interview-feedback', 'conversion'])->default('initial-progress');
            
            // Survey Responses
            $table->boolean('received_interviews')->nullable();
            $table->integer('interviews_count')->nullable();
            $table->integer('applications_count')->nullable();
            $table->text('additional_feedback')->nullable();
            $table->enum('interested_in_interview_practice', ['yes', 'no', 'maybe'])->nullable();
            
            // Email Tracking
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->boolean('email_opened')->default(false);
            $table->timestamp('email_opened_at')->nullable();
            $table->boolean('responded')->default(false);
            $table->timestamp('responded_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_surveys');
    }
};
