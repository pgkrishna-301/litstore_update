<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('architects', function (Blueprint $table) {
            $table->id();
            $table->string('select_architect');
            $table->string('name');
            $table->string('firm_name')->nullable();
            $table->string('email')->unique();
            $table->string('ph_no');
            $table->text('shipping_address');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('architects');
    }
};
