<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'user_id' => 'required|integer', // Ensure user_id is provided
        ]);

        // Create the wishlist entry in the database with `product_id` and `user_id`
        $wishlist = Wishlist::create([
            'product_id' => $validatedData['product_id'],
            'user_id' => $validatedData['user_id'],
        ]);

        return response()->json([
            'success' => true,
            'wishlist' => $wishlist
        ], 201);
    }

    // API method to retrieve wishlist details filtered by `user_id`
  

    public function getAllWishlists()
{
    // Retrieve all wishlist entries from the database
    $wishlists = Wishlist::select('id', 'product_id', 'user_id')->get();

    if ($wishlists->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No wishlist items found.',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $wishlists,
    ], 200);
}

public function getByUserId($user_id)
{
    // Fetch wishlists for the given user_id
    $wishlists = Wishlist::where('user_id', $user_id)
        ->select('id', 'product_id', 'user_id')
        ->get();

    if ($wishlists->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No wishlist items found for this user.',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $wishlists,
    ], 200);
}

public function deleteByProductId($product_id)
{
    // Find the wishlist item by product_id
    $wishlist = Wishlist::where('product_id', $product_id)->first();

    if (!$wishlist) {
        return response()->json([
            'success' => false,
            'message' => 'Wishlist item not found.',
        ], 404);
    }

    // Delete the wishlist item
    $wishlist->delete();

    return response()->json([
        'success' => true,
        'message' => 'Wishlist item deleted successfully.',
    ], 200);
}


    
}
