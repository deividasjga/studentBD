<?php

namespace App\Http\Controllers\Student;

use App\Models\ChallengeModel;
use App\Models\StudentChallengeModel;
use App\Models\RewardModel;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class RewardController extends Controller
{
    public function index()
    {
        $rewards= RewardModel::all();

        return response()->json($rewards);
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
