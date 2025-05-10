<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Architect;
use Illuminate\Support\Facades\Validator;

class ArchitectController extends Controller {
    
    // Store Architect Data
    public function store(Request $request) {
        // Validate Request Data
        $validator = Validator::make($request->all(), [
            'select_architect' => 'required|string',
            'name' => 'required|string|max:255',
            'firm_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|',
            'ph_no' => 'required|string|',
            'shipping_address' => 'required|string',
            'status' => 'nullable|string',
            'creator' => 'nullable|string',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Store Data
        $architect = Architect::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Architect details saved successfully',
            'data' => $architect,
        ], 201);
    }

    // Get All Architects
    public function getAllArchitects() {
        $architects = Architect::all();

        return response()->json([
            'status' => true,
            'message' => 'All architect details fetched successfully',
            'data' => $architects,
        ], 200);
    }

    // Get Single Architect by ID
    public function getArchitectById($id) {
        $architect = Architect::find($id);

        if (!$architect) {
            return response()->json([
                'status' => false,
                'message' => 'Architect not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Architect details fetched successfully',
            'data' => $architect,
        ], 200);
    }

    // Update Architect Data
    public function update(Request $request, $ph_no) {
        // Find architect by phone number
        $architect = Architect::where('ph_no', $ph_no)->first();
    
        if (!$architect) {
            return response()->json([
                'status' => false,
                'message' => 'Architect not found',
            ], 404);
        }
    
        // Validate Request Data
        $validator = Validator::make($request->all(), [
            'select_architect' => 'required|string',
            'name' => 'required|string|max:255',
            'firm_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email',
            'ph_no' => 'required|string|max:15', 
            'shipping_address' => 'required|string',
            'status' => 'nullable|string|',
            'creator' => 'nullable|string|'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // Update Data
        $architect->update($request->all());
    
        return response()->json([
            'status' => true,
            'message' => 'Architect details updated successfully',
            'data' => $architect,
        ], 200);
    }
    
// Update Architect Data
public function updateAll(Request $request) {
    // Validate Request Data
    $validator = Validator::make($request->all(), [
        'select_architect' => '|string',
        'name' => '|string|max:255',
        'firm_name' => 'nullable|string|max:255',
        'email' => 'nullable|string|email',
        'ph_no' => '|string',
        'shipping_address' => '|string',
        'status' => 'nullable|string',
        'creator' => 'nullable|string|'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    // Update all records
    $updatedRows = Architect::query()->update($request->only([
        'select_architect',
        'name',
        'firm_name',
        'email',
        'ph_no',
        'shipping_address',
        'status',
        'creator'
    ]));

    return response()->json([
        'status' => true,
        'message' => 'All architect records updated successfully',
        'updated_rows' => $updatedRows
    ], 200);
}




    // Delete Architect
    public function destroy($id) {
        $architect = Architect::find($id);

        if (!$architect) {
            return response()->json([
                'status' => false,
                'message' => 'Architect not found',
            ], 404);
        }

        $architect->delete();

        return response()->json([
            'status' => true,
            'message' => 'Architect deleted successfully',
        ], 200);
    }

    public function getByCreator($creator)
{
    $architects = Architect::where('creator', $creator)->get();

    if ($architects->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'No architects found for this creator.',
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data' => $architects,
    ]);
}



}
