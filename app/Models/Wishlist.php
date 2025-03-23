<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlists'; 


    // Specify the table name if needed

   
    protected $fillable = [
        'product_id',
        'user_id',
    ];

}
