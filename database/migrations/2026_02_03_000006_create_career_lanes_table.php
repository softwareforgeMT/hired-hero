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
        Schema::create('career_lanes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "Admin Support", "Data Analysis"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('related_titles')->nullable(); // ["Admin Assistant", "Admin Support"]
            $table->json('key_skills')->nullable();
            $table->integer('seniority_level')->nullable(); // 1-5
            $table->string('primary_sector')->nullable();
            $table->json('alternate_sectors')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_lanes');
    }
};
