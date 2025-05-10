<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profession;
use Illuminate\Support\Facades\Validator;

class ProfessionController extends Controller
{
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'select_profession' => 'required|string',
            'name' => 'required|string|max:255',
            'firm_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'ph_no' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new enquiry
        $add_profession = Profession::create($request->all());

        return response()->json([
            'message' => 'Enquiry Created Successfully',
            'data' => $add_profession
        ], 201);
    }

    public function getProfessions()
{
    $professions = Profession::all(); // Fetch all records

    return response()->json([
        'message' => 'Professions retrieved successfully',
        'data' => $professions
    ], 200);
}

}
