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
}
