<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hide;

class HideController extends Controller
{
    // Store Data (POST)
    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|numeric',
            'status' => 'required|boolean' // Ensure status is a boolean (0 or 1)
        ]);

        $hide = Hide::create([
            'user_id' => $request->user_id,
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Status created successfully',
            'data' => $hide
        ], 201);
    }

    // Update Data (EDIT)
    public function update(Request $request, $id) {
        $request->validate([
            'user_id' => 'required|numeric',
            'status' => 'required|boolean'
        ]);

        $hide = Hide::find($id);
        if (!$hide) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $hide->update([
            'user_id' => $request->user_id,
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $hide
        ], 200);
    }
}
