<?php

namespace App\Http\Controllers;

use App\Models\SportLight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SportLightController extends Controller
{
    public function store(Request $request)
    {
        // Validate Request
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file uploads and save to public/storage/uploads
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('uploads', 'public');
        }

        // Save to Database
        $banner = SportLight::create([
            'image' => $validated['image']
        ]);

        return response()->json([
            'message' => 'Image uploaded successfully!',
            'data' => $banner
        ], 201);
    }

    public function getImages()
{
    // Retrieve all records from sport_light table
    $images = SportLight::all()->map(function ($item) {
        $item->image = url('storage/' . $item->image); // Generate full URL for image
        return $item;
    });

    // Return JSON response with image URLs
    return response()->json([
        'message' => 'Images retrieved successfully!',
        'data' => $images
    ], 200);
}

}
