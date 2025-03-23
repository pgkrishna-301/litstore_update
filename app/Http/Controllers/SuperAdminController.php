<?php

namespace App\Http\Controllers;

use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|unique:super_admin,mobile_number',
            'password' => 'required|string|min:6',
        ]);
    
        $superAdmin = SuperAdmin::create([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
        ]);
    
        return response()->json([
            'message' => 'Super Admin registered successfully',
            'data' => $superAdmin
        ], 201);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|',
            'email' => 'nullable|email|',
            'password' => 'nullable|string|min:6',
            'profile_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // Allow only image files
        ]);

        $superAdmin = SuperAdmin::find($id);

        if (!$superAdmin) {
            return response()->json([
                'message' => 'Super Admin not found',
            ], 404);
        }

        // Update details
        if ($request->has('name')) $superAdmin->name = $request->name;
        if ($request->has('mobile_number')) $superAdmin->mobile_number = $request->mobile_number;
        if ($request->has('email')) $superAdmin->email = $request->email;
        if ($request->has('password')) $superAdmin->password = Hash::make($request->password);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if it exists
            if ($superAdmin->profile_image && Storage::disk('public')->exists($superAdmin->profile_image)) {
                Storage::disk('public')->delete($superAdmin->profile_image);
            }

            // Store the new image
            $profileImagePath = $request->file('profile_image')->store('uploads', 'public');
            $superAdmin->profile_image = $profileImagePath;
        }

        $superAdmin->save();

        return response()->json([
            'message' => 'Super Admin updated successfully',
            'data' => $superAdmin,
        ], 200);
    }
    
    public function getAllDetails()
    {
        $superAdmins = SuperAdmin::all();

        return response()->json([
            'message' => 'All Super Admin details retrieved successfully',
            'data' => $superAdmins,
        ], 200);
    }
    public function login(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'password' => 'required|string',
        ]);
    
        $superAdmin = SuperAdmin::where('mobile_number', $request->mobile_number)->first();
    
        if (!$superAdmin || !Hash::check($request->password, $superAdmin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        // Generate API token
        $token = Str::random(60); // Or use bin2hex(random_bytes(30)) as a fallback
        $superAdmin->update(['api_token' => $token]);
    
        return response()->json([
            'message' => 'Login successful',
            'token' => $token
        ], 200);
    }
    
public function logout(Request $request)
{
    $request->validate([
        'mobile_number' => 'required|string',
    ]);

    $superAdmin = SuperAdmin::where('mobile_number', $request->mobile_number)->first();

    if (!$superAdmin) {
        return response()->json(['message' => 'Super Admin not found'], 404);
    }

    $superAdmin->update(['api_token' => null]);

    return response()->json(['message' => 'Logout successful'], 200);
}


     //
}
