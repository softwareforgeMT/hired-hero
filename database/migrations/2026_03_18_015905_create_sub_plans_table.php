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
        Schema::create('sub_plans', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('interval')->nullable();
            $table->string('duration_unit')->nullable();
            $table->unsignedInteger('duration_value')->nullable();

            $table->text('description')->nullable();

            $table->decimal('price', 10, 2)->default(0.00);

            $table->decimal('price_per_unit', 10, 2)->default(0.00);
            $table->decimal('crossed_price_per_unit', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->decimal('crossed_total_price', 10, 2)->default(0.00);

            $table->json('access_section')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_plans');
    }
};
