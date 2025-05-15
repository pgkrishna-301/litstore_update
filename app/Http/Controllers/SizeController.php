<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sizes' => 'required|array',
            'color_name' => 'required|array',
            'location' => 'required|array',
        ]);

        $size = Size::create($validated);

        return response()->json([
            'message' => 'Size record created successfully',
            'data' => $size,
        ], 201);
    }



     public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sizes' => 'sometimes|array',
            'color_name' => 'sometimes|array',
            'location' => 'sometimes|array',
        ]);

        $size = Size::find($id);

        if (!$size) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $size->update($validated);

        return response()->json([
            'message' => 'Size record updated successfully',
            'data' => $size,
        ]);
    }


    public function show($id)
{
    $size = Size::find($id);

    if (!$size) {
        return response()->json(['message' => 'Record not found'], 404);
    }

    return response()->json([
        'message' => 'Size record retrieved successfully',
        'data' => $size
    ]);
}

}
