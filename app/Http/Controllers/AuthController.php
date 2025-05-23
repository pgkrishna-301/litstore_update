<?php

namespace App\Http\Controllers;
      
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|regex:/^[0-9]{10}$/|unique:user,mobile_number',
            'email' => 'nullable|string|email|max:255',
            'password' => 'required|string|min:8',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        // Check if mobile number already exists
        $existingUser = DB::table('user')->where('mobile_number', $request->mobile_number)->first();
        if ($existingUser) {
            return response()->json(['message' => 'Mobile number already registered'], 409);
        }
    
        // Handle file upload
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('uploads', 'public');
        }
    
        // Insert user data into database
        DB::table('user')->insert([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_image' => $profileImagePath,
        ]);
    
        return response()->json(['message' => 'Registration successful'], 201);
    }
    

public function login(Request $request)
{
    $request->validate([
        'mobile_number' => 'required|string|regex:/^[0-9]{10}$/',
        'password' => 'required|string',
    ]);

    // Fetch user by mobile number
    $user = DB::table('user')->where('mobile_number', $request->mobile_number)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Generate API token
    $token = base64_encode(Str::random(40));
    
    // Update the user's record with the generated token
    DB::table('user')->where('mobile_number', $request->mobile_number)->update(['api_token' => $token]);

    // Return the response with the token and user details
    return response()->json([
        'message' => 'Login successful',
        'api_token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'mobile_number' => $user->mobile_number,
            'email' => $user->email,
        ]
    ]);
}

    public function logout(Request $request)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['message' => 'Token missing'], 400);
        }

        DB::table('user')->where('api_token', $token)->update(['api_token' => null]);
        return response()->json(['message' => 'Logout successful']);
    }

 public function getUserProfile(Request $request)
{
    $users = DB::table('user')->get();

    if ($users->isEmpty()) {
        return response()->json(['message' => 'No users found'], 404);
    }

    // Append full image URL
    $users = $users->map(function ($user) {
        $user->profile_image = $user->profile_image 
            ? asset('storage/' . $user->profile_image) 
            : null;
        return $user;
    });

    return response()->json($users);
}

public function getUserById($id)
{
    $user = DB::table('user')->where('id', $id)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Add full URL to profile image
    $user->profile_image = $user->profile_image 
        ? asset('storage/' . $user->profile_image) 
        : null;

    return response()->json([
        'success' => true,
        'data' => $user,
    ], 200);
}


    

   public function updateUser(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:user,id',
        'name' => 'nullable|string|max:255',
        'architect' => 'nullable|string|max:255',
        'mobile_number' => 'nullable|string|regex:/^[0-9]{10}$/',
        'email' => 'nullable|string|email|max:255',
        'password' => 'nullable|string|min:8',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = DB::table('user')->where('id', $request->id)->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    $profileImagePath = $user->profile_image;

    if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('uploads', 'public');
        $profileImagePath = $path; // This will be 'uploads/filename.jpg'
    }

    $updateData = [
        'name' => $request->name ?? $user->name,
        'architect' => $request->architect ?? $user->architect,
        'mobile_number' => $request->mobile_number ?? $user->mobile_number,
        'email' => $request->email ?? $user->email,
        'profile_image' => $profileImagePath,
    ];

    if ($request->password) {
        $updateData['password'] = Hash::make($request->password);
    }

    DB::table('user')->where('id', $request->id)->update($updateData);

    $updatedUser = DB::table('user')->where('id', $request->id)->first();

    return response()->json([
        'success' => true,
        'message' => 'User updated successfully!',
        'data' => $updatedUser,
    ], 200);
}


    public function updateHide (Request $request, $id)
    {
        // Validate input data
        $request->validate([
            'name' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|regex:/^[0-9]{10}$/',
            'email' => 'nullable|string|email|max:255',
            'password' => 'nullable|string|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hide' => 'nullable|string',
            'architect' => 'nullable|string|max:255', // Validate hide field
        ]);

        // Find the user by ID
        $user = DB::table('user')->where('id', $id)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Handle file upload
        $profileImagePath = $user->profile_image; // Keep existing image if not updated
        if ($request->hasFile('profile_image')) {
            $uploadedImage = $request->file('profile_image');
            $profileImagePath = $uploadedImage->move(public_path('uploads'), $uploadedImage->getClientOriginalName());
            $profileImagePath = 'uploads/' . $uploadedImage->getClientOriginalName();
        }

        // Prepare update data
        $updateData = [
            'name' => $request->name ?? $user->name,
            'architect' => $request->architect ?? $user->architect,
            'mobile_number' => $request->mobile_number ?? $user->mobile_number,
            'email' => $request->email ?? $user->email,
            'profile_image' => $profileImagePath,
            'hide' => $request->hide ?? $user->hide, // Add hide field
        ];

        // Update password if provided
        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Perform update
        DB::table('user')->where('id', $id)->update($updateData);

        // Fetch updated user
        $updatedUser = DB::table('user')->where('id', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
            'data' => $updatedUser,
        ], 200);
    }
    
public function getAllUsers(Request $request) 
{
    // Fetch all users
    $users = DB::table('user')->get();

    if ($users->isEmpty()) {
        return response()->json(['message' => 'No users found'], 404);
    }

    // Append full URL to profile_image path
    $users = $users->map(function ($user) {
        if ($user->profile_image) {
            $filename = basename($user->profile_image); // e.g., myphoto.png
            $user->profile_image_url = url('api/profile-image/' . $filename); // Must match route
        } else {
            $user->profile_image_url = null;
        }
        return $user;
    });

    return response()->json([
        'success' => true,
        'data' => $users,
    ], 200);
}



    public function updatearchitectHide(Request $request, $mobile_number)
{
    // Validate input data
    $request->validate([
        'name' => 'nullable|string|max:255',
        'mobile_number' => 'nullable|string|regex:/^[0-9]{10}$/',
        'email' => 'nullable|string|email|max:255',
        'password' => 'nullable|string|min:8',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'hide' => 'nullable|string',
        'architect' => 'nullable|string|max:255', 
    ]);

    // Find the user by architect
    $user = DB::table('user')->where('mobile_number', $mobile_number)->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    // Handle file upload
    $profileImagePath = $user->profile_image; // Keep existing image if not updated
    if ($request->hasFile('profile_image')) {
        $uploadedImage = $request->file('profile_image');
        $profileImagePath = 'uploads/' . $uploadedImage->getClientOriginalName();
        $uploadedImage->move(public_path('uploads'), $uploadedImage->getClientOriginalName());
    }

    // Prepare update data
    $updateData = [
        'name' => $request->name ?? $user->name,
        'mobile_number' => $request->mobile_number ?? $user->mobile_number,
        'email' => $request->email ?? $user->email,
        'profile_image' => $profileImagePath,
        'hide' => $request->hide ?? $user->hide,
        'architect' => $request->architect ?? $user->architect,
    ];

    // Update password if provided
    if ($request->password) {
        $updateData['password'] = Hash::make($request->password);
    }

    // Perform update
    DB::table('user')->where('mobile_number', $mobile_number)->update($updateData);

    // Fetch updated user
    $updatedUser = DB::table('user')->where('mobile_number', $mobile_number)->first();

    return response()->json([
        'success' => true,
        'message' => 'User updated successfully!',
        'data' => $updatedUser,
    ], 200);
}

    
    
public function logoutAllDevices(Request $request)
{
    $token = $request->header('Authorization');
    if (!$token) {
        return response()->json(['message' => 'Token missing'], 400);
    }

    // Get the user associated with the token
    $user = DB::table('user')->where('api_token', $token)->first();
    if (!$user) {
        return response()->json(['message' => 'Invalid token'], 401);
    }

    // Invalidate all tokens for the user by setting `api_token` to null
    DB::table('user')->where('id', $user->id)->update(['api_token' => null]);

    return response()->json(['message' => 'Logged out from all devices'], 200);
}


}
