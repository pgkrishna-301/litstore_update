<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Architect extends Model {
    use HasFactory;

    protected $fillable = [
        'select_architect',
        'name',
        'firm_name',
        'email',
        'ph_no',
        'shipping_address',
        'status',
        'creator'
    ];
}
