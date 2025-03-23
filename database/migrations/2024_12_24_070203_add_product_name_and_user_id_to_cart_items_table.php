<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductNameAndUserIdToCartItemsTable extends Migration
{
    public function up()
    {
        Schema::table('cartitem', function (Blueprint $table) {
            // Add product_name and user_id columns (no foreign key)
            $table->string('product_name');  // Add product_name as string
            $table->unsignedBigInteger('user_id');  // Add user_id as an unsigned integer
        });
    }

    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Drop product_name and user_id columns if rolling back the migration
            $table->dropColumn('product_name');
            $table->dropColumn('user_id');
        });
    }
}
