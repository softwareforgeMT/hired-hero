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
        Schema::create('job_match_email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('placement_profile_id')->constrained()->onDelete('cascade');
            $table->string('selected_role')->comment('The role selected for this week\'s email');
            $table->integer('job_count')->default(0)->comment('Number of jobs included in the email');
            $table->json('job_ids')->nullable()->comment('Array of JobMatch IDs sent in this email');
            $table->timestamp('sent_at')->nullable()->comment('When the email was sent');
            $table->timestamp('last_sent_week')->nullable()->comment('Last week when email was sent');
            $table->timestamps();

            // Ensure we only send one email per user per week
            $table->unique(['user_id', 'placement_profile_id', 'sent_at']);
            $table->index(['user_id', 'sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_match_email_logs');
    }
};
