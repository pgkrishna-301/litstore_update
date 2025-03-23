<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string']);
        $comment = Comment::create([
            'comment' => $request->comment,
            'image_id' => $id,
            'user_id' => 1, // Replace with actual user ID
        ]);

        return response()->json(['message' => 'Comment added successfully!', 'comment' => $comment], 201);
    }
}
