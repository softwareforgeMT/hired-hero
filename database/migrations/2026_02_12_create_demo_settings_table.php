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
        Schema::create('demo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('section')->unique()->comment('Section name: general, social, profile, pages, etc.');
            $table->json('data')->nullable()->comment('Demo data stored as JSON');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_settings');
    }
};
