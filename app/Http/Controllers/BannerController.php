<?php
// app/Http/Controllers/BannerController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'banner_name' => 'required|string|max:255',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Store the image in the 'public' directory
        $imagePath = $request->file('banner_image')->store('banners', 'public');

        // Create new banner record
        $banner = Banner::create([
            'banner_name' => $request->banner_name,
            'banner_image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Banner uploaded successfully!',
            'data' => $banner
        ], 201);
    }
}
