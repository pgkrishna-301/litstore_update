<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cartitem';

    protected $fillable = [
        'product_id',
        'customer_id',
        'banner_image',
        'color_image',
        'color_name',
        'size',
        'size_name',
        'brand',
        'light_type',
        'wattage',
        'mrp',
        'discount',
        'bulb_shape_size',
        'bulb_base',
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
