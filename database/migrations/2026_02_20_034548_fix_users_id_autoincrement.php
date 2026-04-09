<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Fix the users table id column to have AUTO_INCREMENT
        Schema::table('users', function (Blueprint $table) {
            // Modify the id column to be autoIncrement if it isn't already
            DB::statement('ALTER TABLE users MODIFY COLUMN id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert would be complex, so we'll just note it in comments
        // If needed, manually restore the previous schema
    }
};
