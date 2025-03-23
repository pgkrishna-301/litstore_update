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
        Schema::create('add_products', function (Blueprint $table) {
            $table->id();
            $table->string('banner_image')->nullable(); // Nullable if not mandatory
            $table->json('add_image')->nullable(); // JSON to store multiple images in one column
            $table->string('product_category')->nullable(); // Nullable if not mandatory
            $table->string('product_brand')->nullable();
            $table->string('product_name')->nullable(); // Nullable if not mandatory
            $table->text('product_description')->nullable(); // Nullable if not mandatory
            $table->decimal('mrp', 10, 2)->nullable(); // Allow null for optional pricing
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('offer_price', 10, 2)->nullable();
            $table->string('size_name')->nullable();
            $table->string('pack_size')->nullable();
            $table->string('light_type')->nullable();
            $table->string('wattage')->nullable();
            $table->string('special_feature')->nullable();
            $table->string('bulb_shape_size')->nullable();
            $table->string('bulb_base')->nullable();
            $table->string('light_colour')->nullable();
            $table->integer('net_quantity')->nullable();
            $table->string('colour_temperature')->nullable();
            $table->json('color_image')->nullable(); // JSON for multiple color images
            $table->text('about_items')->nullable();
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('add_products');
    }
};
