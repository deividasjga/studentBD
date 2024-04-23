<?php

namespace App\Http\Controllers\Student;

use App\Models\ChallengeModel;
use App\Models\StudentChallengeModel;
use App\Models\RewardModel;
use App\Models\PointModel;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = RewardModel::whereNull('student_id')->get();

        return response()->json($rewards);
    }

    public function getStudentPoints($userId)
    {
        $points = PointModel::where('student_id', $userId)->sum('points');

        return response()->json(['points' => $points]);
    }

    public function subtractPoints(Request $request)
    {
        $userId = $request->userId;
        $pointsPrice = $request->points_price;

        $point = PointModel::where('student_id', $userId)->firstOrFail();

        $point->points -= $pointsPrice;
        $point->save();

        $rewardId = $request->reward_id;
        $reward = RewardModel::findOrFail($rewardId);
        $reward->student_id = $userId;
        $reward->save();
    
        return response()->json(['message' => 'Points subtracted successfully'], 200);
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


    public function getRewardPurchaseHistory($userId)
    {
        $rewardPurchases = RewardModel::where('student_id', $userId)->get();

        return response()->json($rewardPurchases);
    }

}
