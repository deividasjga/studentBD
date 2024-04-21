<?php

namespace App\Http\Controllers\Admin;

use App\Models\ChallengeModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminChallengeController extends Controller
{
    public function index()
    {
        $challenges = ChallengeModel::all();

        return response()->json($challenges);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'challenge_type' => 'required|integer',
            'subject_id' => 'nullable|integer|exists:subject,id',
            'grade_count' => 'nullable|integer',
            'minimum_grade' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'reward_points' => 'required|integer',
        ]);

        $challenge = ChallengeModel::create($validatedData);

        return response()->json(['message' => 'Challenge created successfully', 'challenge' => $challenge], 201);
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'challenge_type' => 'required|integer',
            'subject_id' => 'nullable|integer|exists:subject,id',
            'grade_count' => 'nullable|integer',
            'minimum_grade' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'reward_points' => 'required|integer',
        ]);

        $challenge = ChallengeModel::findOrFail($id);
        $challenge->update($validatedData);

        return response()->json(['message' => 'Challenge updated successfully', 'challenge' => $challenge], 200);
    }

    public function destroy($id)
    {
        $challenge = ChallengeModel::findOrFail($id);
        $challenge->delete();

        return response()->json(['message' => 'Challenge deleted successfully'], 200);
    }


}
