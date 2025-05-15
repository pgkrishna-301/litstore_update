<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['sizes', 'color_name', 'location'];

    // Cast these fields to arrays automatically
    protected $casts = [
        'sizes' => 'array',
        'color_name' => 'array',
        'location' => 'array',
    ];
}
