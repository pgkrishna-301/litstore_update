<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'status', 'cash', 'credit', 'received', 'pending', 'price', 'products','status', 'customer_id'
    ];

    protected $casts = [
        'products' => 'array', // Automatically convert JSON to array
    ];
}

