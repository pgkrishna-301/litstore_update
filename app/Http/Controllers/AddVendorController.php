<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddVendor;

class AddVendorController extends Controller
{
    // Fetch all vendors
    public function index()
    {
        $vendors = AddVendor::all();
        return response()->json($vendors);
    }

    // Store a new vendor
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:add_vendor',
            'ph_no' => 'required|string',
            'address' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $vendor = AddVendor::create($request->all());

        return response()->json(['message' => 'Vendor added successfully', 'vendor' => $vendor], 201);
    }

    // Get a specific vendor by ID
    public function show($id)
    {
        $vendor = AddVendor::find($id);

        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        return response()->json($vendor);
    }

    // Update a vendor
    public function update(Request $request, $id)
    {
        $vendor = AddVendor::find($id);

        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:add_vendors,email,' . $id,
            'ph_no' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'description' => 'nullable|string',
        ]);

        $vendor->update($request->all());

        return response()->json(['message' => 'Vendor updated successfully', 'vendor' => $vendor]);
    }

    // Delete a vendor
    public function destroy($id)
    {
        $vendor = AddVendor::find($id);

        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $vendor->delete();

        return response()->json(['message' => 'Vendor deleted successfully']);
    }
}
