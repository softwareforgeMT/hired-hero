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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., PROMO123
            $table->decimal('discount_percentage', 5, 2); // e.g., 10.50 for 10.50%
            $table->integer('max_usage')->default(1); // How many times the code can be used
            $table->integer('used_count')->default(0); // How many times it has been used
            $table->dateTime('expires_at')->nullable(); // Expiry date
            $table->boolean('active')->default(true); // Active/Inactive status
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Indexes for quick lookup
            $table->index('code');
            $table->index('expires_at');
        });

        // Pivot table to track which users have been assigned which codes
        Schema::create('promo_code_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained('promo_codes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('used')->default(false); // Whether user has used this code
            $table->dateTime('used_at')->nullable(); // When the user used the code
            $table->timestamps();
            
            // Unique constraint so user can't have duplicate promo codes assigned
            $table->unique(['promo_code_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_code_user');
        Schema::dropIfExists('promo_codes');
    }
};
