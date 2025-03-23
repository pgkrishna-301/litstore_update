<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'add_product';

    protected $fillable = [
        'banner_image',
        'add_image',
        'product_category',
        'product_brand',
        'product_name',
        'product_description',
        'mrp',
        'discount',
        'offer_price',
        'size_name',
        'pack_size',
        'light_type',
        'wattage',
        'special_feature',
        'bulb_shape_size',
        'bulb_base',
        'light_colour',
        'net_quantity',
        'colour_temperature',
        'color_image',
        'about_items',
    ];

    protected $casts = [
        'add_image' => 'array',
        'color_image' => 'array',
    ];
}
