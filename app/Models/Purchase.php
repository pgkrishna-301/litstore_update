<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchase';

    protected $fillable = [
        'banner_image',
        'color_image',
        'size',
        'size_name',
        'brand',
        'light_type',
        'wattage',
        'mrp',
        'discount',
        'bulb_shape_size',
        'product_name',
        'user_id',
        'pack_size',
        'qty',
        'location'
    ];

    // Accessor for banner_image
    public function getBannerImageAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }

    // Accessor for color_image
    public function getColorImageAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }
}
