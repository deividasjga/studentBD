<?php

namespace App\Http\Controllers\Admin;

use App\Models\ChallengeModel;
use App\Models\StudentChallengeModel;
use App\Models\RewardModel;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminRewardController extends Controller
{
    public function index()
    {
        $rewards= RewardModel::all();

        return response()->json($rewards);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'points_price' => 'required|integer',
            'code' => 'required|string|unique:reward',
            'valid_until' => 'nullable|date',
        ]);

        $reward = RewardModel::create($request->all());
        return response()->json($reward, 201);
    }


    public function update(Request $request, $id)
    {
        $reward = RewardModel::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'points_price' => 'required|integer',
            'code' => 'required|string|unique:reward,code,' . $reward->id,
            'valid_until' => 'nullable|date',
        ]);

        $reward->update($request->all());

        return response()->json($reward, 200);
    }

    public function destroy($id)
    {
        $reward = RewardModel::findOrFail($id);
        $reward->delete();

        return response()->json(['message' => 'Reward deleted successfully'], 200);
    }

}
