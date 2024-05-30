<?php

namespace App\Http\Controllers\Admin;

use App\Models\ChallengeModel;
use App\Models\StudentChallengeModel;
use App\Models\RewardModel;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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
            'valid_until' => 'nullable|date|after:today',
        ]);

        $encryptedCode = Crypt::encryptString($request->input('code'));
        $request->merge(['code' => $encryptedCode]);

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
            'valid_until' => 'nullable|date|after:today',
        ]);

        $code = $request->input('code');
        if ($code) {
            $encryptedCode = Crypt::encryptString($code);
            $request->merge(['code' => $encryptedCode]);
        }

        $reward->update($request->all());

        return response()->json($reward, 200);
    }

    public function destroy($id)
    {
        $reward = RewardModel::findOrFail($id);
        $reward->delete();

        return response()->json(['message' => 'Reward deleted successfully'], 200);
    }


    public function decryptCode(Request $request)
    {
        $code = $request->input('code');
        try {
            $decryptedCode = Crypt::decryptString($code);
            return response()->json($decryptedCode, 200);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->json(['error' => 'Decryption failed.'], 500);
        }
    }

}
