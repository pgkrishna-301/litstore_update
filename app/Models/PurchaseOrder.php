<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $table = 'purchase_order';

    protected $fillable = ['order_id' ,'user_id', 'status', 'cash', 'credit', 'received', 'pending', 'products', 'price','customer_id','description', 'select_vendor','email', 'ph_no', 'address', 'delivery_date'];

    protected $casts = [
        'products' => 'array', // Automatically convert JSON to array
    ];
}
