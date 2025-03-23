<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    use HasFactory;

    protected $table = 'super_admin';

    protected $fillable = [
        'name',
        'mobile_number',
        'password',
        'email',
        'profile_image',
        'api_token',
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];
}
