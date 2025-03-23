<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function like($id)
    {
        $like = Like::firstOrCreate([
            'image_id' => $id,
            'user_id' => 1, // Replace with actual user ID
        ]);

        return response()->json(['message' => 'Image liked successfully!', 'like' => $like], 201);
    }
}
