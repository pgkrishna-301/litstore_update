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
        Schema::create('add_profession', function (Blueprint $table) {
            $table->id();
            $table->string('select_profession');
            $table->string('name');
            $table->string('firm_name')->nullable();
            $table->string('email')->unique();
            $table->string('ph_no');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_profession');
    }
};
