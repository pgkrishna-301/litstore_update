<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model {
    use HasFactory;
    protected $table = 'vendor'; 
    protected $fillable = [
        'select_vendor',
        'email',
        'ph_no',
        'email',
        'address',
        'description'
    ];
}
