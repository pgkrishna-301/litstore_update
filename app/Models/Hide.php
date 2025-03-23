<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hide extends Model {
    use HasFactory;

    protected $table = 'hide';

    protected $fillable = ['user_id', 'status']; // Include 'status'
}
