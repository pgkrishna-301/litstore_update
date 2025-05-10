<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shipping'; // Explicitly set the table name to 'shipping'

    protected $fillable = [
        'user_id', 'address', 'city', 'pincode', 'phone_number',
    ];
}
