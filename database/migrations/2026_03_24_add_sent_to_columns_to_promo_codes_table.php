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
        Schema::table('promo_codes', function (Blueprint $table) {
            // Store user IDs as JSON array
            $table->json('sent_to_user_ids')->nullable()->after('is_bulk');
            // Store custom (non-registered) emails as JSON array
            $table->json('sent_to_custom_emails')->nullable()->after('sent_to_user_ids');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropColumn(['sent_to_user_ids', 'sent_to_custom_emails']);
        });
    }
};
