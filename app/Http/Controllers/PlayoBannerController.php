<?php
namespace App\Http\Controllers;

use App\Models\PlayoBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class PlayoBannerController extends Controller
{
    public function store(Request $request)
    {
        // Validate Request
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file uploads and save to public/storage/uploads
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        // Save to Database
        $banner = PlayoBanner::create([
            'image' => $imagePath
        ]);

        return response()->json([
            'message' => 'Image uploaded successfully!',
            'data' => $banner
        ], 201);
    }

    public function getImages()
    {
        // Retrieve all records from playo_banners table
        $images = PlayoBanner::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'image' => asset('storage/' . $item->image), // Full URL
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];
        });

        return response()->json([
            'message' => 'Images retrieved successfully!',
            'data' => $images
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Log incoming request data for debugging
        Log::info('Request Data:', $request->all());
    
        // Find the product by ID
        $product = PlayoBanner::find($id);
    
        // If the product is not found, return a 404 response
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }
    
        // Log the found product details
        Log::info('Product Found:', $product->toArray());
    
        // Handle file upload for 'banner_image'
        if ($request->hasFile('image')) {
            $file = $request->file('image');
    
            // Validate the file if needed
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg|max:2048', // Example validation
            ]);
    
            // Save the file to the public path (or storage)
            $path = $file->store('uploads/image', 'public');
            $product->image = $path;
    
            Log::info('Banner Image Uploaded:', ['path' => $path]);
        }
    
        // Handle file upload for 'color_image'
   
    
        // Handle other fields
        $product->fill($request->except(['image']));
        $product->save();
    
        // Log the updated product details
        Log::info('Product Updated:', $product->toArray());
    
        // Return a success response
        return response()->json([
            'success' => true,
            'data' => $product,
        ], 200);
    }
    

    
    public function destroy($id)
    {
        $banner = PlayoBanner::find($id);
        if (!$banner) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        // Delete image from storage
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        // Delete record from database
        $banner->delete();

        return response()->json(['message' => 'Image deleted successfully!'], 200);
    }
}
