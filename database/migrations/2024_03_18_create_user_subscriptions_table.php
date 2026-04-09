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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('sub_plans')->onDelete('cascade');
            $table->string('plan_slug')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_id')->nullable();
            $table->string('token')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->json('access_section')->nullable();
            $table->json('features_used')->nullable(); // Track feature usage (cover_letters, job_searches, etc)
            $table->string('status')->default('active'); // active, expired, cancelled
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            // Indexes for quick lookups
            $table->index('user_id');
            $table->index('plan_id');
            $table->index('expires_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
