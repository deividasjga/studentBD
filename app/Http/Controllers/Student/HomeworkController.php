<?php

namespace App\Http\Controllers\Student;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;
use App\Models\HomeworkModel;
use Illuminate\Support\Facades\Validator;

class HomeworkController extends Controller
{
    public function index($userId)
    {
        $student = User::findOrFail($userId);
        $studentClassId = $student->student_class_id;

        $homework = HomeworkModel::with('teacher', 'class', 'subject')
            ->where('class_id', $studentClassId)
            ->get();

        return response()->json($homework);
    }
}