<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class PurchaseOrderController extends Controller
{
   
    
  
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'order_id' => 'required|string|max:255',
            'user_id' => 'nullable|integer',
            'status' => 'nullable|integer',
            'cash' => 'nullable|string|max:255',
            'credit' => 'nullable|string|max:255',
            'received' => 'nullable|string|max:255',
            'pending' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:255',
            'products' => 'required|array',
            'customer_id' => 'nullable|string',
            'description' => 'nullable|string',
            'select_vendor' => 'nullable|string',
            'email' => 'nullable|string',
            'ph_no' => 'nullable|string',
            'address' => 'nullable|string',
        ]);
    
        $productsArray = [];
    
        foreach ($request->products as $index => $product) {
            $productData = [
                'product_name' => $product['product_name'] ?? '',
                'qty' => $product['qty'] ?? 0,
                'size_name' => $product['size_name'] ?? '',
                'size' => $product['size'] ?? '',
                'price' => $product['price'] ?? 0,
                'brand' => $product['brand'] ?? '',
                'banner_image' => null,
                'color_image' => null
            ];
    
            // Handle banner image upload
            if ($request->hasFile("products.$index.banner_image")) {
                $bannerImagePath = $request->file("products.$index.banner_image")->store('uploads', 'public');
                $productData['banner_image'] = asset("storage/$bannerImagePath");
            } elseif (!empty($product['banner_image'])) {
                $productData['banner_image'] = $product['banner_image']; // Preserve existing path
            }
    
            // Handle color image upload
            if ($request->hasFile("products.$index.color_image")) {
                $colorImagePath = $request->file("products.$index.color_image")->store('uploads', 'public');
                $productData['color_image'] = asset("storage/$colorImagePath");
            } elseif (!empty($product['color_image'])) {
                $productData['color_image'] = $product['color_image'];
            }
    
            $productsArray[] = $productData;
        }
    
        // Store order in database
        $order = PurchaseOrder::create([
            'order_id' => $validatedData['order_id'],
            'user_id' => $validatedData['user_id'] ?? null,
            'products' => json_encode($productsArray),
            'status' => $validatedData['status'] ?? null,
            'cash' => $validatedData['cash'] ?? null,
            'credit' => $validatedData['credit'] ?? null,
            'received' => $validatedData['received'] ?? null,
            'pending' => $validatedData['pending'] ?? null,
            'price' => $validatedData['price'] ?? null,
            'customer_id' => $validatedData['customer_id'] ?? null,
            'description' => $validatedData['description'] ?? null,
            'select_vendor' => $validatedData['select_vendor'] ?? null,
            'email' => $validatedData['email'] ?? null,
            'ph_no' => $validatedData['ph_no'] ?? null,
            'address' => $validatedData['address'] ?? null,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Order stored successfully!',
            'data' => [
                'order_id' => $order->order_id,
                'user_id' => $order->user_id,
                'status' => $order->status,
                'cash' => $order->cash,
                'credit' => $order->credit,
                'received' => $order->received,
                'pending' => $order->pending,
                'price' => $order->price,
                'customer_id' => $order->customer_id,
                'description' => $order->description,
                'select_vendor' => $order->select_vendor,
                'email' => $order->email,
                'ph_no' => $order->ph_no,
                'address' => $order->address,
                'products' => json_decode($order->products, true),
                'id' => $order->id
            ]
        ], 201);
    }
    public function getOrderByOrderId($order_id)
{
    // Retrieve the order by order_id
    $order = PurchaseOrder::where('order_id', $order_id)->first();

    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Order retrieved successfully!',
        'data' => [
            'order_id' => $order->order_id,
            'user_id' => $order->user_id,
            'status' => $order->status,
            'cash' => $order->cash,
            'credit' => $order->credit,
            'received' => $order->received,
            'pending' => $order->pending,
            'price' => $order->price,
            'customer_id' => $order->customer_id,
            'description' => $order->description,
            'select_vendor' => $order->select_vendor,
            'email' => $order->email,
            'ph_no' => $order->ph_no,
            'address' => $order->address,
            'products' => json_decode($order->products, true),
            'id' => $order->id
        ]
    ], 200);
}

    
    public function updateorderId(Request $request, $order_id)
    {
        // Validate the request
        $validatedData = $request->validate([
            'user_id' => 'nullable|integer',
            'status' => 'nullable|integer',
            'cash' => 'nullable|string|max:255',
            'credit' => 'nullable|string|max:255',
            'received' => 'nullable|string|max:255',
            'pending' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:255',
            'products' => 'nullable|array',
            'customer_id' => 'nullable|string',
            'description' => 'nullable|string',
            'select_vendor' => 'nullable|string',
            'email' => 'nullable|string',
            'ph_no' => 'nullable|string',
            'address' => 'nullable|string',
            'delivery_date' => 'nullable|date', // Add delivery date validation
        ]);
    
        // Find the order by order_id
        $order = PurchaseOrder::where('order_id', $order_id)->first();
    
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found!'
            ], 404);
        }
    
        $productsArray = json_decode($order->products, true) ?? [];
    
        if ($request->has('products')) {
            $productsArray = [];
    
            foreach ($request->products as $index => $product) {
                $productData = [
                    'product_name' => $product['product_name'] ?? '',
                    'qty' => $product['qty'] ?? 0,
                    'size_name' => $product['size_name'] ?? '',
                    'size' => $product['size'] ?? '',
                    'price' => $product['price'] ?? 0,
                    'brand' => $product['brand'] ?? '',
                    'banner_image' => $product['banner_image'] ?? null,
                    'color_image' => $product['color_image'] ?? null
                ];
    
                // Handle banner image upload
                if ($request->hasFile("products.$index.banner_image")) {
                    $bannerImagePath = $request->file("products.$index.banner_image")->store('uploads', 'public');
                    $productData['banner_image'] = asset("storage/$bannerImagePath");
                }
    
                // Handle color image upload
                if ($request->hasFile("products.$index.color_image")) {
                    $colorImagePath = $request->file("products.$index.color_image")->store('uploads', 'public');
                    $productData['color_image'] = asset("storage/$colorImagePath");
                }
    
                $productsArray[] = $productData;
            }
        }
    
        // Update order details
        $order->update([
            'user_id' => $validatedData['user_id'] ?? $order->user_id,
            'status' => $validatedData['status'] ?? $order->status,
            'cash' => $validatedData['cash'] ?? $order->cash,
            'credit' => $validatedData['credit'] ?? $order->credit,
            'received' => $validatedData['received'] ?? $order->received,
            'pending' => $validatedData['pending'] ?? $order->pending,
            'price' => $validatedData['price'] ?? $order->price,
            'customer_id' => $validatedData['customer_id'] ?? $order->customer_id,
            'description' => $validatedData['description'] ?? $order->description,
            'select_vendor' => $validatedData['select_vendor'] ?? $order->select_vendor,
            'email' => $validatedData['email'] ?? $order->email,
            'ph_no' => $validatedData['ph_no'] ?? $order->ph_no,
            'address' => $validatedData['address'] ?? $order->address,
            'delivery_date' => $validatedData['delivery_date'] ?? $order->delivery_date, // Add delivery date
            'products' => json_encode($productsArray),
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully!',
            'data' => [
                'order_id' => $order->order_id,
                'user_id' => $order->user_id,
                'status' => $order->status,
                'cash' => $order->cash,
                'credit' => $order->credit,
                'received' => $order->received,
                'pending' => $order->pending,
                'price' => $order->price,
                'customer_id' => $order->customer_id,
                'description' => $order->description,
                'select_vendor' => $order->select_vendor,
                'email' => $order->email,
                'ph_no' => $order->ph_no,
                'address' => $order->address,
                'delivery_date' => $order->delivery_date, // Return delivery date
                'products' => json_decode($order->products, true),
                'id' => $order->id
            ]
        ], 200);
    }
    
    
    
    
    
    

    public function getAll()
    {
        $PurchaseOrders = PurchaseOrder::all();

        return response()->json([
            'success' => true,
            'message' => 'Order details retrieved successfully.',
            'data' => $PurchaseOrders,
        ], 200);
    }

    public function getById($id)
    {
        // Find the order detail by ID
        $PurchaseOrder = PurchaseOrder::find($id);
    
        // Check if the record exists
        if (!$PurchaseOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Order detail not found.',
            ], 404);
        }
    
        // Return the order detail if found
        return response()->json([
            'success' => true,
            'message' => 'Order detail retrieved successfully.',
            'data' => $PurchaseOrder,
        ], 200);
    }
    
    public function getByUserId($userId)
{
    // Retrieve all order details based on the user_id
    $PurchaseOrders = PurchaseOrder::where('user_id', $userId)->get();

    // Check if the orders exist for the given user_id
    if ($PurchaseOrders->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No order details found for this user.',
        ], 404);
    }

    // Return the order details for the specified user_id
    return response()->json([
        'success' => true,
        'message' => 'Order details retrieved successfully.',
        'data' => $PurchaseOrders,
    ], 200);
}



    public function update(Request $request, $id)
    {
        // Find the order detail by its ID
        $PurchaseOrder = PurchaseOrder::find($id);
    
        if (!$PurchaseOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Order detail not found.',
            ], 404);
        }
    
        // Validate the request data
        $validatedData = $request->validate([
            'product_name' => 'nullable|string|max:255',
            'qty' => 'nullable|integer',
            'size_name' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'user_id' => 'nullable|integer',
            'brand' => 'nullable|string|max:255',
            'banner_image' => 'nullable|file|mimes:jpeg,png,jpg',
            'color_image' => 'nullable|file|mimes:jpeg,png,jpg',
            'order_id' => 'nullable|string|max:255',
            'status' => 'nullable|integer',
            'cash' => 'nullable|string|max:255',
            'credit' => 'nullable|string|max:255',
            'received' => 'nullable|string|max:255',
            'pending' => 'nullable|string|max:255',
        ]);
    
        // Check if a new banner image is uploaded and store it
        if ($request->hasFile('banner_image')) {
            $validatedData['banner_image'] = $request->file('banner_image')->store('order', 'public');
        }
    
        // Check if a new color image is uploaded and store it
        if ($request->hasFile('color_image')) {
            $validatedData['color_image'] = $request->file('color_image')->store('order', 'public');
        }
    
        // Update the order detail with the validated data
        $PurchaseOrder->update($validatedData);
    
        // Return a success response with the updated order detail
        return response()->json([
            'success' => true,
            'message' => 'Order detail updated successfully.',
            'data' => $PurchaseOrder,
        ], 200);
    }
    

}

