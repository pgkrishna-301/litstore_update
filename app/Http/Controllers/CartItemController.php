<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Storage;

class CartItemController extends Controller
{
public function store(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'nullable|numeric',
        'customer_id' => 'nullable|string',
        'qty' => 'nullable|numeric',
        'banner_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'color_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'size' => 'nullable|string',
        'size_name' => 'nullable|string',
        'color_name' => 'nullable|string',
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
        'location' => 'nullable|string',
    ]);

    // ✅ Skip duplicate check if product_id is null or 0
    $productId = $validated['product_id'] ?? null;
    if (!is_null($productId) && $productId != 0) {
        $existing = CartItem::where('product_id', $productId)
            ->where('size_name', $validated['size_name'] ?? null)
            ->where('color_name', $validated['color_name'] ?? null)
            ->where('customer_id', $validated['customer_id'] ?? null)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'This item already exists in this customer\'s cart.'
            ], 409); // 409 Conflict
        }
    }

    // ✅ Handle banner image
    if ($request->hasFile('banner_image')) {
        $validated['banner_image'] = $request->file('banner_image')->store('uploads', 'public');
    }

    // ✅ Handle color image
    if ($request->hasFile('color_image')) {
        $validated['color_image'] = $request->file('color_image')->store('uploads', 'public');
    }

    $cartItem = CartItem::create($validated);

    return response()->json([
        'message' => 'Cart item created successfully.',
        'data' => $cartItem
    ], 201);
}


    


    public function update(Request $request, $id)
{
    // Validate incoming request
    $validated = $request->validate([
        'product_id' => 'nullable|numeric',
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
    $cartItem = CartItem::find($id);

    if (!$cartItem) {
        return response()->json(['message' => 'Cart item not found.'], 404);
    }

    // Handle file uploads and update storage
    if ($request->hasFile('banner_image')) {
        // Delete the old file if exists
        if ($cartItem->banner_image) {
            Storage::disk('public')->delete($cartItem->banner_image);
        }
        $validated['banner_image'] = $request->file('banner_image')->store('uploads', 'public');
    }

    if ($request->hasFile('color_image')) {
        if ($cartItem->color_image) {
            Storage::disk('public')->delete($cartItem->color_image);
        }
        $validated['color_image'] = $request->file('color_image')->store('uploads', 'public');
    }

    // Update cart item with new values
    $cartItem->update($validated);

    // Return success response
    return response()->json([
        'message' => 'Cart item updated successfully.',
        'data' => $cartItem
    ], 200);
}
public function addToCart(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'nullable|numeric',
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

    $cartItem = CartItem::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Item added to cart successfully',
        'data' => $cartItem
    ]);
}
public function getByCustomerId($customerId)
{
    $cartItems = CartItem::where('customer_id', $customerId)->get();

    return response()->json([
        'message' => 'Cart items fetched successfully.',
        'data' => $cartItems
    ]);
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

    public function destroyByCustomer(string $customer_id)
    {
        $deleted = CartItem::where('customer_id', $customer_id)->delete();
    
        return $deleted
            ? response()->json(['message' => "Deleted {$deleted} cart item(s)."], 200)
            : response()->json(['message' => 'No cart items found.'], 404);
    }
    
    
}
