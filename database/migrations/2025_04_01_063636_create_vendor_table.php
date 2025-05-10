<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->string('select_vendor'); // Vendor selection field
            $table->string('email')->unique(); // Email field
            $table->string('ph_no'); // Phone number field
            $table->text('description')->nullable(); // Description field (nullable)
            $table->timestamps(); // Created at & Updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor');
    }
};
