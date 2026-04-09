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
        Schema::create('placement_workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('placement_profile_id')->constrained()->onDelete('cascade');
            
            $table->integer('step_number'); // 1-7
            $table->enum('status', ['pending', 'in-progress', 'completed', 'skipped'])->default('pending');
            $table->json('step_data')->nullable(); // Store step-specific data
            
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_workflow_steps');
    }
};
