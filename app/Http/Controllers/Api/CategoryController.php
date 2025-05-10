<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Method to store a category
    public function categorystore(Request $request)
    {
        $request->validate([
            'category_name' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the image upload if present
        $imagePath = $request->hasFile('category_image') 
            ? $request->file('category_image')->store('category_images', 'public') 
            : null;

        // Create a new category
        $category = Category::create([
            'category_name' => $request->category_name,
            'section' => $request->section,
            'category_image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category added successfully!',
            'data' => $category,
        ], 201);
    }

    // Method to retrieve category list
    public function categorylist()
    {
        $categories = Category::all(['category_name', 'section', 'category_image']); // Include required columns

        return response()->json([
            'success' => true,
            'data' => $categories,
        ], 200);
    }
}
