<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('hide', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->unsignedBigInteger('user_id'); // Normal user_id column
            $table->boolean('status')->default(0); // Status column (default 0)
            $table->timestamps(); // Created_at and Updated_at timestamps
        });
    }

    public function down() {
        Schema::dropIfExists('hide');
    }
};

