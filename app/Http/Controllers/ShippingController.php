<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    // Store Shipping Data
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'address' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        try {
            $shipping = Shipping::create($validated);

            return response()->json([
                'message' => 'Shipping information stored successfully!',
                'data' => $shipping,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while storing the shipping information.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Update Shipping Data
    public function update(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'address' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|string',
            'phone_number' => 'required|string',
        ]);
    
        try {
            // Find the record by user_id
            $shipping = Shipping::where('user_id', $validated['user_id'])->first();
    
            if (!$shipping) {
                return response()->json([
                    'message' => 'Shipping record not found for the given user_id.',
                ], 404);
            }
    
            // Update the record
            $shipping->update($validated);
    
            return response()->json([
                'message' => 'Shipping information updated successfully!',
                'data' => $shipping,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the shipping information.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    // Get Shipping Data by user_id
public function show($user_id)
{
    try {
        $shipping = Shipping::where('user_id', $user_id)->first();

        if (!$shipping) {
            return response()->json([
                'message' => 'Shipping record not found for the given user_id.',
            ], 404);
        }

        return response()->json([
            'message' => 'Shipping information retrieved successfully!',
            'data' => $shipping,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while retrieving the shipping information.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    // Get All Shipping Data
    public function index()
    {
        try {
            $shippings = Shipping::all(); // Retrieve all shipping records

            return response()->json([
                'message' => 'Shipping information retrieved successfully!',
                'data' => $shippings,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the shipping information.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Destroy Shipping Data
public function destroy($user_id)
{
    try {
        // Find the record by user_id
        $shipping = Shipping::where('user_id', $user_id)->first();

        if (!$shipping) {
            return response()->json([
                'message' => 'Shipping record not found for the given user_id.',
            ], 404);
        }

        // Delete the record
        $shipping->delete();

        return response()->json([
            'message' => 'Shipping information deleted successfully!',
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while deleting the shipping information.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
