<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;
    protected $table = 'quotes';

    protected $fillable = ['order_id' ,'user_id', 'status', 'cash', 'credit', 'received', 'pending', 'products', 'price','customer_id','customer_name', 'architect_name','email', 'phone_number', 'shipping_address', 'delivery_date'];

    protected $casts = [
        'products' => 'array', // Automatically convert JSON to array
    ];
}
