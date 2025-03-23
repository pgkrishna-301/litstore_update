<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;

class CartItemController extends Controller
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
        $cartItem = CartItem::create($validated);

        // Return success response
        return response()->json([
            'message' => 'Cart item created successfully.',
            'data' => $cartItem
        ], 201);
    }

    public function index(Request $request, $userId)
    {
        // Fetch cart items filtered by user_id
        $cartItems = CartItem::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'No cart items found for the given user.',
            ], 404);
        }

        // Add additional data (e.g., computed discounts or related data)
        $cartItems->map(function ($item) {
            $item->final_price = $item->mrp - ($item->mrp * ($item->discount ?? 0) / 100);
            return $item;
        });

        // Return the filtered data
        return response()->json([
            'message' => 'Cart items fetched successfully.',
            'data' => $cartItems,
        ], 200);
    }

    public function show($id)
    {
        // Fetch a specific cart item by ID
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json([
                'message' => 'Cart item not found.',
            ], 404);
        }

        // Add additional data (e.g., computed fields or related items)
        $cartItem->final_price = $cartItem->mrp - ($cartItem->mrp * ($cartItem->discount ?? 0) / 100);

        // Return the data
        return response()->json([
            'message' => 'Cart item fetched successfully.',
            'data' => $cartItem,
        ], 200);
    }

    public function destroy($id)
    {
        // Find the cart item by ID
        $cartItem = CartItem::find($id);

        // Check if the cart item exists
        if (!$cartItem) {
            return response()->json([
                'message' => 'Cart item not found.',
            ], 404);
        }

        // Delete the cart item
        $cartItem->delete();

        // Return success response
        return response()->json([
            'message' => 'Cart item deleted successfully.',
        ], 200);
    }
}
