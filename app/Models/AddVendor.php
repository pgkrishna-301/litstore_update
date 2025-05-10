<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddVendor extends Model
{
    use HasFactory;

    protected $table = 'add_vendor';

    protected $fillable = [
        'name',
        'email',
        'ph_no',
        'address',
        'description',
    ];
}
