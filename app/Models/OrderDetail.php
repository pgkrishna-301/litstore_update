<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['order_id' ,'user_id', 'status','discount_status', 'cash', 'credit', 'received', 'pending', 'products', 'price','customer_id','customer_name', 'architect_name','email', 'phone_number', 'shipping_address', 'delivery_date', 'discount', 'discount_status', 'discount_price'];

    protected $casts = [
        'products' => 'array', // Automatically convert JSON to array
    ];
}
