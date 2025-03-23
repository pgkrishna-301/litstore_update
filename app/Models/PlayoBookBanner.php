<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayoBookBanner extends Model
{
    protected $table = 'playo_bookbanner';
    protected $fillable = ['image'];
}