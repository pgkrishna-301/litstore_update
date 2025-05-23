<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class OrderDetailController extends Controller
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
            'discount' => 'nullable|string|max:255',
            'discount_status' => 'nullable|integer|max:255',
            'discount_price' => 'nullable|string|max:255',
            'products' => 'required|array',
            'customer_id' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'architect_name' => 'nullable|string',
            'email' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'shipping_address' => 'nullable|string',
        ]);
    
        $productsArray = [];
    
        foreach ($request->products as $index => $product) {
            $productData = [
                'product_name' => $product['product_name'] ?? '',
                'color_name' => $product['color_name'] ?? '',
                'location' => $product['location'] ?? '',
                'qty' => $product['qty'] ?? 0,
                'product_id' => $product['product_id'] ?? 0,
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
        $order = OrderDetail::create([
            'order_id' => $validatedData['order_id'],
            'user_id' => $validatedData['user_id'] ?? null,
            'products' => json_encode($productsArray),
            'status' => $validatedData['status'] ?? null,
            'cash' => $validatedData['cash'] ?? null,
            'credit' => $validatedData['credit'] ?? null,
            'received' => $validatedData['received'] ?? null,
            'pending' => $validatedData['pending'] ?? null,
            'price' => $validatedData['price'] ?? null,
            'discount' => $validatedData['discount'] ?? null,
            'discount_status' => $validatedData['discount_status'] ?? null,
            'discount_price' => $validatedData['discount_price'] ?? null,
            'customer_id' => $validatedData['customer_id'] ?? null,
            'customer_name' => $validatedData['customer_name'] ?? null,
            'architect_name' => $validatedData['architect_name'] ?? null,
            'email' => $validatedData['email'] ?? null,
            'phone_number' => $validatedData['phone_number'] ?? null,
            'shipping_address' => $validatedData['shipping_address'] ?? null,
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
                'discount' => $order->discount,
                'discount_status' => $order->discount_status,
                'discount_price' => $order->discount_price,
                'customer_id' => $order->customer_id,
                'customer_name' => $order->customer_name,
                'architect_name' => $order->architect_name,
                'email' => $order->email,
                'phone_number' => $order->phone_number,
                'shipping_address' => $order->shipping_address,
                'products' => json_decode($order->products, true),
                'id' => $order->id
            ]
        ], 201);
    }
 
public function getOrderByOrderId($order_id) 
{
    // Retrieve the order by order_id
    $order = OrderDetail::where('order_id', $order_id)->first();

    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order not found'
        ], 404);
    }

    // Decode products and add banner_image_url and color_image_url
    $products = json_decode($order->products, true);

    foreach ($products as &$product) {
        if (!empty($product['banner_image'])) {
            $filename = basename($product['banner_image']);
            $product['banner_image_url'] = url("api/banner-image/{$filename}");
        } else {
            $product['banner_image_url'] = null;
        }

        if (!empty($product['color_image'])) {
            $filename = basename($product['color_image']);
            $product['color_image_url'] = url("api/color-image/{$filename}");
        } else {
            $product['color_image_url'] = null;
        }
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
            'discount' => $order->discount,
            'discount_price' => $order->discount_price,
            'pending' => $order->pending,
            'price' => $order->price,
            'customer_id' => $order->customer_id,
            'customer_name' => $order->customer_name,
            'architect_name' => $order->architect_name,
            'email' => $order->email,
            'phone_number' => $order->phone_number,
            'shipping_address' => $order->shipping_address,
            'products' => $products,
            'created_at' => $order->created_at,
            'delivery_date' => $order->delivery_date,
            'id' => $order->id
        ]
    ], 200);
}

    

 public function getAll(Request $request)
{
    // Fetch all orders
    $orders = OrderDetail::all();

    if ($orders->isEmpty()) {
        return response()->json(['message' => 'No orders found'], 404);
    }

    // Process each order and append full URLs to image paths
    $orders = $orders->map(function ($order) {
        $decodedProducts = json_decode($order->products, true);

        if (is_array($decodedProducts)) {
            foreach ($decodedProducts as &$product) {
                // Process banner image URL
                if (!empty($product['banner_image']) && !str_starts_with($product['banner_image'], 'http')) {
                    $filename = basename($product['banner_image']); // e.g., myphoto.png
                    $product['banner_image_url'] = url('api/banner-image/' . $filename); // Generate full URL
                } else {
                    $product['banner_image_url'] = null;
                }

                // Process color image URL
                if (!empty($product['color_image']) && !str_starts_with($product['color_image'], 'http')) {
                    $filename = basename($product['color_image']); // e.g., colorphoto.png
                    $product['color_image_url'] = url('api/color-image/' . $filename); // Generate full URL
                } else {
                    $product['color_image_url'] = null;
                }
            }
        }

        // Reassign the processed products with the new URLs
        $order->products = $decodedProducts;
        return $order;
    });

    return response()->json([
        'success' => true,
        'message' => 'All orders with full image URLs retrieved successfully.',
        'data' => $orders,
    ], 200);
}


public function updateOrderByCustomerId(Request $request, $customer_id)
{
    // Find the order by customer_id
    $order = OrderDetail::where('customer_id', $customer_id)->first();

    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order not found for the given customer ID',
        ], 404);
    }

    // Validate the request
    $validatedData = $request->validate([
        'order_id' => 'nullable|string|max:255',
        'user_id' => 'nullable|integer',
        'status' => 'nullable|integer',
        'cash' => 'nullable|string|max:255',
        'credit' => 'nullable|string|max:255',
        'received' => 'nullable|string|max:255',
        'pending' => 'nullable|string|max:255',
        'price' => 'nullable|string|max:255',
        'discount' => 'nullable|string|max:255',
        'discount_price' => 'nullable|string|max:255',
        'products' => 'nullable|array',
        'customer_name' => 'nullable|string',
        'architect_name' => 'nullable|string',
        'email' => 'nullable|string',
        'phone_number' => 'nullable|string',
        'shipping_address' => 'nullable|string',
    ]);

    // Handle product array if provided
    if (isset($validatedData['products'])) {
        $productsArray = [];

        foreach ($validatedData['products'] as $index => $product) {
            $productData = [
                'product_name' => $product['product_name'] ?? '',
                'product_id' => $product['product_id'] ?? 0,
                'qty' => $product['qty'] ?? 0,
                'size_name' => $product['size_name'] ?? '',
                'size' => $product['size'] ?? '',
                'price' => $product['price'] ?? 0,
                'brand' => $product['brand'] ?? '',
                'banner_image' => $product['banner_image'] ?? null,
                'color_image' => $product['color_image'] ?? null,
            ];

            // Handle file uploads
            if ($request->hasFile("products.$index.banner_image")) {
                $bannerImagePath = $request->file("products.$index.banner_image")->store('uploads', 'public');
                $productData['banner_image'] = asset("storage/$bannerImagePath");
            }

            if ($request->hasFile("products.$index.color_image")) {
                $colorImagePath = $request->file("products.$index.color_image")->store('uploads', 'public');
                $productData['color_image'] = asset("storage/$colorImagePath");
            }

            $productsArray[] = $productData;
        }

        $order->products = json_encode($productsArray);
    }

    // Update other fields if provided
    foreach ($validatedData as $key => $value) {
        if ($key !== 'products') {
            $order->$key = $value;
        }
    }

    // Save updated order
    $order->save();

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
            'discount' => $order->discount,
            'discount_price' => $order->discount_price,
            'customer_id' => $order->customer_id,
            'customer_name' => $order->customer_name,
            'architect_name' => $order->architect_name,
            'email' => $order->email,
            'phone_number' => $order->phone_number,
            'shipping_address' => $order->shipping_address,
            'products' => json_decode($order->products, true),
            'id' => $order->id
        ]
    ], 200);
}


public function getOrdersByCustomerId($customer_id)
{
    // Retrieve all orders for the given customer_id
    $orders = OrderDetail::where('customer_id', $customer_id)->get();

    if ($orders->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No orders found for this customer.',
        ], 404);
    }

    // Format each order into an array
    $orderData = $orders->map(function ($order) {
        return [
            'order_id' => $order->order_id,
            'user_id' => $order->user_id,
            'status' => $order->status,
            'cash' => $order->cash,
            'credit' => $order->credit,
            'received' => $order->received,
            'pending' => $order->pending,
            'price' => $order->price,
            'customer_id' => $order->customer_id,
            'customer_name' => $order->customer_name,
            'discount' => $order->discount,
            'discount_price' => $order->discount_price,
            'architect_name' => $order->architect_name,
            'email' => $order->email,
            'phone_number' => $order->phone_number,
            'shipping_address' => $order->shipping_address,
            'products' => json_decode($order->products, true),
            'id' => $order->id,
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'Orders retrieved successfully!',
        'data' => $orderData
    ], 200);
}


    
    public function updateorderId(Request $request, $order_id)
    {
        // Validate the request
        $validatedData = $request->validate([
            'user_id' => 'nullable|integer',
            'status' => 'nullable|integer',
            'discount_status' => 'nullable|integer',
            'cash' => 'nullable|string|max:255',
            'credit' => 'nullable|string|max:255',
            'received' => 'nullable|string|max:255',
            'pending' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:255',
            'products' => 'nullable|array',
            'customer_id' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'architect_name' => 'nullable|string',
            'email' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'delivery_date' => 'nullable|date', // Add delivery date validation
        ]);
    
        // Find the order by order_id
        $order = OrderDetail::where('order_id', $order_id)->first();
    
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
                    'product_id' => $product['product_id'] ?? 0,
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
           
            '_discount_status' => $validatedData['status'] ?? $order->status,
            'cash' => $validatedData['cash'] ?? $order->cash,
            'credit' => $validatedData['credit'] ?? $order->credit,
            'received' => $validatedData['received'] ?? $order->received,
            'pending' => $validatedData['pending'] ?? $order->pending,
            'price' => $validatedData['price'] ?? $order->price,
            'customer_id' => $validatedData['customer_id'] ?? $order->customer_id,
            'customer_name' => $validatedData['customer_name'] ?? $order->customer_name,
            'architect_name' => $validatedData['architect_name'] ?? $order->architect_name,
            'email' => $validatedData['email'] ?? $order->email,
            'phone_number' => $validatedData['phone_number'] ?? $order->phone_number,
            'shipping_address' => $validatedData['shipping_address'] ?? $order->shipping_address,
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
                'discount_status' => $order->status,
                'cash' => $order->cash,
                'credit' => $order->credit,
                'received' => $order->received,
                'pending' => $order->pending,
                'price' => $order->price,
                'customer_id' => $order->customer_id,
                'customer_name' => $order->customer_name,
                'architect_name' => $order->architect_name,
                'email' => $order->email,
                'phone_number' => $order->phone_number,
                'shipping_address' => $order->shipping_address,
                'delivery_date' => $order->delivery_date, // Return delivery date
                'products' => json_decode($order->products, true),
                'id' => $order->id
            ]
        ], 200);
    }
    
    
    
    
    
    


    public function getById($id)
    {
        // Find the order detail by ID
        $orderDetail = OrderDetail::find($id);
    
        // Check if the record exists
        if (!$orderDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Order detail not found.',
            ], 404);
        }
    
        // Return the order detail if found
        return response()->json([
            'success' => true,
            'message' => 'Order detail retrieved successfully.',
            'data' => $orderDetail,
        ], 200);
    }
    
    public function getByUserId($userId)
{
    // Retrieve all order details based on the user_id
    $orderDetails = OrderDetail::where('user_id', $userId)->get();

    // Check if the orders exist for the given user_id
    if ($orderDetails->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No order details found for this user.',
        ], 404);
    }

    // Return the order details for the specified user_id
    return response()->json([
        'success' => true,
        'message' => 'Order details retrieved successfully.',
        'data' => $orderDetails,
    ], 200);
}



public function update(Request $request, $id)
{
    $order = OrderDetail::find($id);

    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order not found'
        ], 404);
    }

    // Validation
    $validatedData = $request->validate([
        'user_id' => 'nullable|integer',
        'status' => 'nullable|integer',
       
        'discount_status' => 'nullable|integer',
        'cash' => 'nullable|string|max:255',
        'credit' => 'nullable|string|max:255',
        'received' => 'nullable|string|max:255',
        'pending' => 'nullable|string|max:255',
        'price' => 'nullable|string|max:255',
        'discount' => 'nullable|string|max:255',
        'discount_price' => 'nullable|string|max:255',
        'products' => 'nullable|array',
        'customer_id' => 'nullable|string',
        'customer_name' => 'nullable|string',
        'architect_name' => 'nullable|string',
        'email' => 'nullable|string',
        'phone_number' => 'nullable|string',
        'shipping_address' => 'nullable|string',
    ]);

    $updateData = [];

    foreach ($validatedData as $key => $value) {
        if ($key !== 'products') {
            $updateData[$key] = $value;
        }
    }

    // Handle products array
    if (isset($validatedData['products']) && is_array($validatedData['products'])) {
        $productsArray = [];

        foreach ($validatedData['products'] as $index => $product) {
            $productData = [
                'product_name' => $product['product_name'] ?? '',
                'qty' => $product['qty'] ?? 0,
                'product_id' => $product['product_id'] ?? 0,
                'size_name' => $product['size_name'] ?? '',
                'size' => $product['size'] ?? '',
                'price' => $product['price'] ?? 0,
                'brand' => $product['brand'] ?? '',
                'banner_image' => null,
                'color_image' => null
            ];

            // Banner image
            if ($request->hasFile("products.$index.banner_image")) {
                $bannerImagePath = $request->file("products.$index.banner_image")->store('uploads', 'public');
                $productData['banner_image'] = asset("storage/$bannerImagePath");
            } elseif (!empty($product['banner_image'])) {
                $productData['banner_image'] = $product['banner_image'];
            }

            // Color image
            if ($request->hasFile("products.$index.color_image")) {
                $colorImagePath = $request->file("products.$index.color_image")->store('uploads', 'public');
                $productData['color_image'] = asset("storage/$colorImagePath");
            } elseif (!empty($product['color_image'])) {
                $productData['color_image'] = $product['color_image'];
            }

            $productsArray[] = $productData;
        }

        $updateData['products'] = json_encode($productsArray);
    }

    $order->update($updateData);

    return response()->json([
        'success' => true,
        'message' => 'Order updated successfully!',
        'data' => $order->fresh()
    ]);
}


public function destroy($order_id)
{
    // Get the first (oldest) row with this order_id
    $order = OrderDetail::where('order_id', $order_id)->orderBy('id', 'asc')->first();

    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order not found',
        ], 404);
    }

    $order->delete();

    return response()->json([
        'success' => true,
        'message' => 'Oldest order with order_id deleted successfully!',
    ], 200);
}






}

