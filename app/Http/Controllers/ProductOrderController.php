<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class ProductOrderController extends Controller
{
    public function store(Request $request)
    {
        // Validate Request
        $request->validate([
            'user_id' => 'required|integer',
            'cash' => 'required|numeric',
            'credit' => 'required|numeric',
            'received' => 'required|numeric',
            'pending' => 'required|numeric',
            'products' => 'required|array', // Store as JSON
        ]);

        // Store Order in Single Table
        $order = Order::create([
            'user_id' => $request->user_id,
            'status' => 1,
            'cash' => $request->cash,
            'credit' => $request->credit,
            'received' => $request->received,
            'pending' => $request->pending,
            'products' => json_encode($request->products), // Store as JSON
        ]);

        // Response
        return response()->json([
            'success' => true,
            'message' => 'Order stored successfully!',
            'data' => $order
        ], 201);
    }
}
