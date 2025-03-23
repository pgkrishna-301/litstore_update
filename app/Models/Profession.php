<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    use HasFactory;

    protected $table = 'add_profession'; // Set the correct table name

    protected $fillable = [
        'select_profession',
        'name',
        'firm_name',
        'email',
        'ph_no',
    ];
}
