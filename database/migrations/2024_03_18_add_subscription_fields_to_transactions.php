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
        Schema::table('transactions', function (Blueprint $table) {
            // Add subscription_id if it doesn't exist
            if (!Schema::hasColumn('transactions', 'subscription_id')) {
                $table->foreignId('subscription_id')->nullable()->after('order_id')->constrained('user_subscriptions')->onDelete('set null');
            }

            // Add plan_id if it doesn't exist
            if (!Schema::hasColumn('transactions', 'plan_id')) {
                $table->foreignId('plan_id')->nullable()->after('subscription_id')->constrained('sub_plans')->onDelete('set null');
            }

            // Add amount if it doesn't exist
            if (!Schema::hasColumn('transactions', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('plan_id');
            }

            // Add payment_method if it doesn't exist
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('amount');
            }

            // Add payment_id if it doesn't exist
            if (!Schema::hasColumn('transactions', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('payment_method');
            }

            // Add transaction_type if it doesn't exist
            if (!Schema::hasColumn('transactions', 'transaction_type')) {
                $table->string('transaction_type')->nullable()->after('payment_id');
            }

            // Add status if it doesn't exist
            if (!Schema::hasColumn('transactions', 'status')) {
                $table->string('status')->default('pending')->after('transaction_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeignIdIf('subscription_id');
            $table->dropForeignIdIf('plan_id');
            $table->dropColumnIf('subscription_id');
            $table->dropColumnIf('plan_id');
            $table->dropColumnIf('amount');
            $table->dropColumnIf('payment_method');
            $table->dropColumnIf('payment_id');
            $table->dropColumnIf('transaction_type');
            $table->dropColumnIf('status');
        });
    }
};
