<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->string('label')->nullable();
            $table->string('photo')->nullable();
            $table->text('details')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->index('parent_id');
            $table->index('status');
            $table->index('slug');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
