<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('status')->default(1);
            $table->decimal('cash', 10, 2);
            $table->decimal('credit', 10, 2);
            $table->decimal('received', 10, 2);
            $table->decimal('pending', 10, 2);
            $table->json('products'); // Store products as JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
