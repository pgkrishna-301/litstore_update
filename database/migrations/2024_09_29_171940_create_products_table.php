<?php

// database/migrations/2024_09_29_000000_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing ID field
            $table->string('name'); // String field for the product name
            $table->decimal('price', 8, 2); // Decimal field for price with 8 digits and 2 decimal places
            $table->text('description')->nullable(); // Text field for description (nullable)
            $table->timestamps(); // Creates `created_at` and `updated_at` timestamp fields
        });
    }

    public function down()
    {
        Schema::dropIfExists('products'); // Drops the products table
    }
}
