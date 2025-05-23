<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    public function productStored(Request $request)
    {
        $validatedData = $request->validate([
            'banner_image' => 'nullable|file|image|max:2048',
        'add_image' => 'nullable|array',
        'add_image.*' => 'nullable|file|image|max:2048',
            'product_category' => 'nullable|string',
            'product_brand' => 'nullable|string',
            'product_name' => 'nullable|string',
            'product_description' => 'nullable|string',
            'mrp' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'offer_price' => 'nullable|numeric',
            'offer_product' => 'nullable|numeric',
    
            'size_name' => 'nullable|array',
            'size_name.*.size_name' => 'nullable|string',
            'size_name.*.price' => 'nullable|integer',
            'size_name.*.qty' => 'nullable|integer',
    
            'color_image' => 'nullable|array',
            'color_image.*.image' => 'nullable|file|image|max:2048',
            'color_image.*.qty' => 'nullable|integer',
            'color_image.*.name' => 'nullable|string',
            'color_image.*.size' => 'nullable|string',
            'color_image.*.price' => 'nullable|integer',
    
            'pack_size' => 'nullable|string',
            'light_type' => 'nullable|string',
            'wattage' => 'nullable|string',
            'special_feature' => 'nullable|string',
            'bulb_shape_size' => 'nullable|string',
            'bulb_base' => 'nullable|string',
            'light_colour' => 'nullable|string',
            'net_quantity' => 'nullable|integer',
            'colour_temperature' => 'nullable|string',
            'about_items' => 'nullable|string',
            'discount_status' => 'nullable|integer',
        ]);
    
        // Banner image
        if ($request->hasFile('banner_image')) {
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads', 'public');
        }
    
        // Handle additional images
        if ($request->hasFile('add_image')) {
            $validatedData['add_image'] = [];
            foreach ($request->file('add_image') as $file) {
                $validatedData['add_image'][] = $file->store('uploads', 'public');
            }
            $validatedData['add_image'] = json_encode($validatedData['add_image']);
        }
    
    
        // Color images
        if ($request->has('color_image')) {
            $colorImages = [];
            foreach ($request->input('color_image') as $index => $color) {
                $imageUrl = null;
    
                if ($request->hasFile("color_image.$index.image")) {
                    $file = $request->file("color_image.$index.image");
                    $imagePath = $file->store('uploads', 'public');
                    $imageUrl = asset("storage/$imagePath");
                }
    
                $colorImages[] = [
                    'image' => $imageUrl,
                    'qty' => $color['qty'] ?? 0,
                    'name' => $color['name'] ?? '',
                    'size' => $color['size'] ?? '',
                    'price' => $color['price'] ?? ''
                ];
            }
    
            $validatedData['color_image'] = $colorImages;
        }
    
        // Size info
        if ($request->has('size_name')) {
            $validatedData['size_name'] = $request->input('size_name');
        }
    
        // Store product
        $product = Product::create($validatedData);
    
        return response()->json([
            'success' => true,
            'data' => $product,
        ], 201);
    }
    
    


// Helper method to parse "Size1:price, Size2:price" into associative array



public function updateProduct(Request $request, $id)
{
    Log::info('Request Data:', $request->all());

    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found.',
        ], 404);
    }

    Log::info('Product Found:', $product->toArray());

    // Handle banner image
    if ($request->hasFile('banner_image')) {
        $request->validate([
            'banner_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('banner_image')->store('uploads', 'public');
        $product->banner_image = $path;

        Log::info('Banner Image Uploaded:', ['path' => $path]);
    }

    // Handle add_image uploads
   // Handle add_image uploads
if ($request->hasFile('add_image')) {
    $request->validate([
        'add_image' => 'array',
        'add_image.*' => 'image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $newImages = [];
    foreach ($request->file('add_image') as $file) {
        $newImages[] = $file->store('uploads', 'public');
    }

    // Overwrite old images
    $product->add_image = json_encode($newImages);
    Log::info('Additional Images Replaced:', $newImages);
}


    // Handle color images
    if ($request->has('color_image')) {
        $colorImages = [];
        foreach ($request->input('color_image') as $index => $color) {
            $imageUrl = $color['image'] ?? null;

            if ($request->hasFile("color_image.$index.image")) {
                $file = $request->file("color_image.$index.image");
                $imagePath = $file->store('uploads', 'public');
                $imageUrl = asset("storage/$imagePath");
            }

            $colorImages[] = [
                'image' => $imageUrl,
                'qty' => $color['qty'] ?? 0,
                'name' => $color['name'] ?? '',
                'size' => $color['size'] ?? '',
                'price' => $color['price'] ?? 0,
            ];
        }

        $product->color_image = $colorImages;
    }

    // Handle size_name
    if ($request->filled('size_name')) {
        $input = $request->size_name;

        if (is_array($input)) {
            $product->size_name = $input;
        } else {
            $decoded = json_decode($input, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $product->size_name = $decoded;
            } else {
                $product->size_name = $this->parseSizeInput($input);
            }
        }
    }

    // Handle pack_size
    if ($request->filled('pack_size')) {
        $input = $request->pack_size;

        $decoded = json_decode($input, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $product->pack_size = json_encode($decoded);
        } else {
            $product->pack_size = json_encode($this->parseSizeInput($input));
        }
    }

    // Update remaining fields
    $product->fill($request->except([
        'banner_image',
        'add_image',
        'color_image',
        'size_name',
        'pack_size',
    ]));

    $product->save();

    Log::info('Product Updated:', $product->toArray());

    return response()->json([
        'success' => true,
        'data' => $product,
    ], 200);
}


// Same parsing function used in productStored


    
    

public function getAllProducts()
{
    $products = Product::all();

    $products = $products->map(function ($product) {
        // Handle banner image URL
        if (!empty($product->banner_image)) {
            $filename = basename($product->banner_image);
            $product->banner_image_url = url('api/banner-image/' . $filename);
        } else {
            $product->banner_image_url = null;
        }

        // Handle additional images
        $addImages = [];
        if (!empty($product->add_image)) {
            $decodedImages = json_decode($product->add_image, true);
            if (is_array($decodedImages)) {
                foreach ($decodedImages as $img) {
                    $addImages[] = url('api/product_add-image/' . basename($img));
                }
            }
        }
        $product->add_image_url = $addImages;

        // Handle color images
        $colorImages = [];
        if (!empty($product->color_image)) {
            $decodedColors = is_array($product->color_image)
                ? $product->color_image
                : json_decode($product->color_image, true);

            if (is_array($decodedColors)) {
                foreach ($decodedColors as $color) {
                    if (!empty($color['image'])) {
                        $color['image_url'] = url('api/product-image/' . basename($color['image']));
                    } else {
                        $color['image_url'] = null;
                    }
                    $colorImages[] = $color;
                }
            }
        }
        $product->color_image = $colorImages;

        return $product;
    });

    return response()->json([
        'success' => true,
        'data' => $products,
    ], 200);
}




   public function getProductById($id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found.',
        ], 404);
    }

    // Handle banner image URL
    if (!empty($product->banner_image)) {
        $filename = basename($product->banner_image);
        $product->banner_image_url = url('api/product-image/' . $filename);
    } else {
        $product->banner_image_url = null;
    }

    // Handle additional images
    $addImages = [];
    if (!empty($product->add_image)) {
        $decodedImages = json_decode($product->add_image, true);
        if (is_array($decodedImages)) {
            foreach ($decodedImages as $img) {
                $addImages[] = url('api/product_add-image/' . basename($img));
            }
        }
    }
    $product->add_image_url = $addImages;

    // Handle color images
    $colorImages = [];
    if (!empty($product->color_image)) {
        $decodedColors = is_array($product->color_image)
            ? $product->color_image
            : json_decode($product->color_image, true);

        if (is_array($decodedColors)) {
            foreach ($decodedColors as $color) {
                if (!empty($color['image'])) {
                    $color['image_url'] = url('api/productcolor-image/' . basename($color['image']));
                } else {
                    $color['image_url'] = null;
                }
                $colorImages[] = $color;
            }
        }
    }
    $product->color_image = $colorImages;

    return response()->json([
        'success' => true,
        'data' => $product,
    ], 200);
}

    /**
 * Delete a product by ID.
 */
public function deleteProduct($id)
{
    // Log the incoming delete request
    Log::info('Delete Request for Product ID:', ['id' => $id]);

    // Find the product by ID
    $product = Product::find($id);

    // If the product is not found, return a 404 response
    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found.',
        ], 404);
    }

    // Delete associated files if they exist
    if ($product->banner_image && Storage::disk('public')->exists($product->banner_image)) {
        Storage::disk('public')->delete($product->banner_image);
        Log::info('Banner image deleted:', ['path' => $product->banner_image]);
    }

    if ($product->color_image) {
        $colorImages = json_decode($product->color_image, true);
        if (is_array($colorImages)) {
            foreach ($colorImages as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                    Log::info('Color image deleted:', ['path' => $imagePath]);
                }
            }
        }
    }

    // Delete the product
    $product->delete();

    // Log the deletion
    Log::info('Product deleted successfully:', ['id' => $id]);

    // Return a success response
    return response()->json([
        'success' => true,
        'message' => 'Product deleted successfully.',
    ], 200);
}

}
