<?php

namespace App\Http\Controllers\Student;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\ChallengeModel;
use App\Models\StudentChallengeModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;
use Illuminate\Support\Facades\Validator;

class ChallengeController extends Controller
{
    public function getStudent($userId)
    {
        $student = User::findOrFail($userId);
        return response()->json($student);
    }

    public function getMyChallenges($studentId)
    {
        $user = User::findOrFail($studentId);

        $challenges = ChallengeModel::whereHas('studentChallenges', function ($query) use ($user) {
            $query->where('student_id', $user->id);
        })->with(['studentChallenges' => function ($query) use ($user) {
            $query->where('student_id', $user->id);
        }])->get();
        
        return response()->json($challenges);
    }
}