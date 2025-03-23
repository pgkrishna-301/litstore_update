<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->nullable();
            $table->integer('qty')->nullable();
            $table->string('size_name')->nullable();
            $table->string('size')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('brand')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('color_image')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
