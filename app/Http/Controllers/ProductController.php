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
            'size_name' => 'nullable|array',
            'size_name.*' => 'nullable|string',
            'pack_size' => 'nullable|array',
            'pack_size.*' => 'nullable|string',
            'light_type' => 'nullable|string',
            'wattage' => 'nullable|string',
            'special_feature' => 'nullable|string',
            'bulb_shape_size' => 'nullable|string',
            'bulb_base' => 'nullable|string',
            'light_colour' => 'nullable|string',
            'net_quantity' => 'nullable|integer',
            'colour_temperature' => 'nullable|string',
            'color_image' => 'nullable|array',
            'color_image.*' => 'nullable|file|image|max:2048',
            'about_items' => 'nullable|string',
        ]);

        if ($request->hasFile('banner_image')) {
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads', 'public');
        }

        if ($request->hasFile('add_image')) {
            $validatedData['add_image'] = [];
            foreach ($request->file('add_image') as $file) {
                $validatedData['add_image'][] = $file->store('uploads', 'public');
            }
            $validatedData['add_image'] = json_encode($validatedData['add_image']);
        }

        if ($request->hasFile('color_image')) {
            $validatedData['color_image'] = [];
            foreach ($request->file('color_image') as $file) {
                $validatedData['color_image'][] = $file->store('uploads', 'public');
            }
            $validatedData['color_image'] = json_encode($validatedData['color_image']);
        }

        if (isset($validatedData['size_name'])) {
            $validatedData['size_name'] = json_encode($validatedData['size_name']);
        }

        if (isset($validatedData['pack_size'])) {
            $validatedData['pack_size'] = json_encode($validatedData['pack_size']);
        }

        $product = Product::create($validatedData);

        return response()->json([
            'success' => true,
            'data' => $product,
        ], 201);
    }

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

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');

            $request->validate([
                'banner_image' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $path = $file->store('uploads/banner_images', 'public');
            $product->banner_image = $path;

            Log::info('Banner Image Uploaded:', ['path' => $path]);
        }

        if ($request->hasFile('color_image')) {
            $validatedData = [];
            foreach ($request->file('color_image') as $file) {
                $request->validate([
                    'color_image.*' => 'image|mimes:jpeg,png,jpg|max:2048',
                ]);

                $path = $file->store('uploads/color_images', 'public');
                $validatedData[] = $path;

                Log::info('Color Image Uploaded:', ['path' => $path]);
            }
            $product->color_image = json_encode($validatedData);
        }

        if ($request->has('size_name')) {
            $product->size_name = json_encode($request->size_name);
        }

        if ($request->has('pack_size')) {
            $product->pack_size = json_encode($request->pack_size);
        }

        $product->fill($request->except(['banner_image', 'color_image', 'size_name', 'pack_size']));
        $product->save();

        Log::info('Product Updated:', $product->toArray());

        return response()->json([
            'success' => true,
            'data' => $product,
        ], 200);
    }
    
    


    public function getAllProducts()
    {
        $products = Product::all();

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
