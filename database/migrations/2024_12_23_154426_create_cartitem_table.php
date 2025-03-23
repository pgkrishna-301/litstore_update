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
        Schema::create('cartitem', function (Blueprint $table) {
            $table->id();
            $table->string('banner_image')->nullable();
            $table->string('color_image')->nullable();
            $table->string('size')->nullable();
            $table->string('size_name')->nullable();
            $table->string('brand')->nullable();
            $table->string('light_type')->nullable();
            $table->string('wattage')->nullable();
            $table->decimal('mrp', 8, 2)->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->string('bulb_shape_size')->nullable();
            $table->string('product_name')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('pack_size')->nullable();
            $table->integer('qty')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cartitem');
    }
};
