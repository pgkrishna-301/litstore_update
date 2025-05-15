<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'banner_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // 2MB max file size
            'color_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'size' => 'nullable|string',
            'size_name' => 'nullable|string',
            'pack_size' => 'nullable|string',
            'brand' => 'nullable|string',
            'light_type' => 'nullable|string',
            'wattage' => 'nullable|string',
            'mrp' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'bulb_shape_size' => 'nullable|string',
            'bulb_base' => 'nullable|string',
            'product_name' => 'nullable|string',
            'user_id' => 'nullable|string',
            'qty' => 'nullable|numeric',
            'location' => 'nullable|string',
        ]);

        // Handle file uploads and save to public/storage/uploads
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('uploads', 'public');
        }

        if ($request->hasFile('color_image')) {
            $validated['color_image'] = $request->file('color_image')->store('uploads', 'public');
        }

        // Save the data in the database
        $Purchase = Purchase::create($validated);

        // Return success response
        return response()->json([
            'message' => 'Cart item created successfully.',
            'data' => $Purchase
        ], 201);
    }

    public function update(Request $request, $id)
{
    // Validate incoming request
    $validated = $request->validate([
        'banner_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // 2MB max file size
        'color_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'size' => 'nullable|string',
        'size_name' => 'nullable|string',
        'pack_size' => 'nullable|string',
        'brand' => 'nullable|string',
        'light_type' => 'nullable|string',
        'wattage' => 'nullable|string',
        'mrp' => 'nullable|numeric',
        'discount' => 'nullable|numeric',
        'bulb_shape_size' => 'nullable|string',
        'bulb_base' => 'nullable|string',
        'product_name' => 'nullable|string',
        'user_id' => 'nullable|string',
        'qty' => 'nullable|numeric',
        'location' => 'nullable|string',
    ]);

    // Find the cart item by ID
    $Purchase = Purchase::find($id);

    if (!$Purchase) {
        return response()->json(['message' => 'Cart item not found.'], 404);
    }

    // Handle file uploads and update storage
    if ($request->hasFile('banner_image')) {
        // Delete the old file if exists
        if ($Purchase->banner_image) {
            Storage::disk('public')->delete($Purchase->banner_image);
        }
        $validated['banner_image'] = $request->file('banner_image')->store('uploads', 'public');
    }

    if ($request->hasFile('color_image')) {
        if ($Purchase->color_image) {
            Storage::disk('public')->delete($Purchase->color_image);
        }
        $validated['color_image'] = $request->file('color_image')->store('uploads', 'public');
    }

    // Update cart item with new values
    $Purchase->update($validated);

    // Return success response
    return response()->json([
        'message' => 'Cart item updated successfully.',
        'data' => $Purchase
    ], 200);
}
public function addToCart(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|integer',
        'product_name' => 'required|string',
        'qty' => 'required|integer',
        'size_name' => 'nullable|string',
        'size' => 'nullable|string',
        'brand' => 'nullable|string',
        'mrp' => 'required|string',
        'banner_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Ensure it's an image file
    ]);

    // Handle file uploads and update storage
    if ($request->hasFile('banner_image')) {
        $validated['banner_image'] = $request->file('banner_image')->store('uploads', 'public');
    }

    $Purchase = Purchase::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Item added to cart successfully',
        'data' => $Purchase
    ]);
}


  public function index(Request $request, $userId)
{
    // Fetch cart items filtered by user_id
    $purchases = Purchase::where('user_id', $userId)->get();

    if ($purchases->isEmpty()) {
        return response()->json([
            'message' => 'No cart items found for the given user.',
        ], 404);
    }

    // Process each item
    $purchases = $purchases->map(function ($item) {
        // Compute final price with discount
        $item->final_price = $item->mrp - ($item->mrp * ($item->discount ?? 0) / 100);

        // Append full banner_image URL if file exists
        if (!empty($item->banner_image) && !str_starts_with($item->banner_image, 'http')) {
            $filename = basename($item->banner_image);
            $item->banner_image_url = url('api/banner-image/' . $filename);
        } else {
            $item->banner_image_url = null;
        }

        // Append full color_image URL if file exists
        if (!empty($item->color_image) && !str_starts_with($item->color_image, 'http')) {
            $filename = basename($item->color_image);
            $item->color_image_url = url('api/color-image/' . $filename);
        } else {
            $item->color_image_url = null;
        }

        return $item;
    });

    // Return the filtered and formatted data
    return response()->json([
        'success' => true,
        'message' => 'Cart items fetched successfully.',
        'data' => $purchases,
    ], 200);
}


    public function show($id)
    {
        // Fetch a specific cart item by ID
        $Purchase = Purchase::find($id);

        if (!$Purchase) {
            return response()->json([
                'message' => 'Cart item not found.',
            ], 404);
        }

        // Add additional data (e.g., computed fields or related items)
        $Purchase->final_price = $Purchase->mrp - ($Purchase->mrp * ($Purchase->discount ?? 0) / 100);

        // Return the data
        return response()->json([
            'message' => 'Cart item fetched successfully.',
            'data' => $Purchase,
        ], 200);
    }

    public function destroy($id)
    {
        // Find the cart item by ID
        $Purchase = Purchase::find($id);

        // Check if the cart item exists
        if (!$Purchase) {
            return response()->json([
                'message' => 'Cart item not found.',
            ], 404);
        }

        // Delete the cart item
        $Purchase->delete();

        // Return success response
        return response()->json([
            'message' => 'Cart item deleted successfully.',
        ], 200);
    }
}
