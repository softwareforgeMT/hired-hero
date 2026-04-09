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
        Schema::table('placement_profiles', function (Blueprint $table) {
            // Add new fields if they don't exist
            if (!Schema::hasColumn('placement_profiles', 'job_languages')) {
                $table->json('job_languages')->nullable()->after('job_level');
            }
            
            if (!Schema::hasColumn('placement_profiles', 'email')) {
                $table->string('email')->nullable()->after('job_languages');
            }
            
            if (!Schema::hasColumn('placement_profiles', 'extracted_skills')) {
                $table->json('extracted_skills')->nullable()->after('skills');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('placement_profiles', function (Blueprint $table) {
            $table->dropColumn(['job_languages', 'email', 'extracted_skills']);
        });
    }
};
