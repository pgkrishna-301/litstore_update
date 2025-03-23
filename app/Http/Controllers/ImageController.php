<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Image;


class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate(['image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048']);
        $imagePath = $request->file('image')->store('images', 'public');

        $image = Image::create([
            'image_path' => $imagePath,
            'user_id' => 1, // You can replace this with the actual user ID
        ]);

        return response()->json(['message' => 'Image uploaded successfully!', 'image' => $image], 201);
    }

    public function index()
    {
        $images = Image::with(['comments', 'likes'])->get();
        return response()->json($images);
    }
}
