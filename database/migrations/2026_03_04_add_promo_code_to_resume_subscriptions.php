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
        Schema::table('resume_subscriptions', function (Blueprint $table) {
            // Add promo code reference
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete()->after('amount');
            $table->decimal('discount_amount', 8, 2)->nullable()->after('promo_code_id');
            $table->decimal('original_amount', 8, 2)->nullable()->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resume_subscriptions', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\PromoCode::class);
            $table->dropColumn(['promo_code_id', 'discount_amount', 'original_amount']);
        });
    }
};
