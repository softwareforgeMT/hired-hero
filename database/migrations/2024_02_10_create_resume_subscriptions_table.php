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
        Schema::create('resume_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('stripe_subscription_id')->nullable()->unique();
            $table->string('stripe_customer_id')->nullable();
            $table->enum('plan_type', ['weekly', 'monthly'])->default('weekly');
            $table->enum('status', ['active', 'canceled', 'expired', 'pending'])->default('pending');
            $table->decimal('amount', 8, 2);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->string('payment_method')->nullable();

            
            $table->string('stripe_product_id')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('user_id');
            $table->index('status');
            $table->index('expires_at');
        });

        Schema::table('placement_profiles', function (Blueprint $table) {
            // Add fields to track if user has paid for resume builder
            $table->boolean('has_paid_resume_builder')->default(false)->after('extracted_skills');
            $table->foreignId('active_subscription_id')->nullable()->constrained('resume_subscriptions')->nullOnDelete()->after('has_paid_resume_builder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('placement_profiles', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\ResumeSubscription::class);
            $table->dropColumn(['has_paid_resume_builder', 'active_subscription_id']);
        });
        
        Schema::dropIfExists('resume_subscriptions');
    }
};
