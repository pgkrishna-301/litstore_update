<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    // Fetch all vendors
    public function index()
    {
        $vendor = Vendor::all();
        return response()->json($vendor);
    }

    // Store a new vendor
    public function store(Request $request)
    {
        $request->validate([
            'select_vendor' => 'required|string',
            'email' => 'required|email|unique:vendor',
            'ph_no' => 'required|string',
            'address' => 'required|string', // Added address field
            'description' => 'nullable|string',
        ]);

        $vendor = Vendor::create([
            'select_vendor' => $request->select_vendor,
            'email' => $request->email,
            'ph_no' => $request->ph_no,
            'address' => $request->address, // Save address
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Vendor created successfully', 'vendor' => $vendor], 201);
    }

    // Get a specific vendor by ID
    public function show($id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        return response()->json($vendor);
    }

    // Update a vendor
    public function update(Request $request, $id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $request->validate([
            'select_vendor' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:vendor,email,' . $id,
            'ph_no' => 'sometimes|required|string',
            'address' => 'sometimes|required|string', // Added address field
            'description' => 'nullable|string',
        ]);

        $vendor->update($request->all());

        return response()->json(['message' => 'Vendor updated successfully', 'vendor' => $vendor]);
    }

    // Delete a vendor
    public function destroy($id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $vendor->delete();

        return response()->json(['message' => 'Vendor deleted successfully']);
    }
}
