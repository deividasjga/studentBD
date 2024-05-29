<?php

namespace App\Http\Controllers\Parent;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;
use App\Models\ParentStudentModel;
use App\Models\HomeworkModel;
use Illuminate\Support\Facades\Validator;

class ParentHomeworkController extends Controller
{
    public function getClassHomework($classId)
    {
        $homework = HomeworkModel::with('teacher', 'class', 'subject')
        ->where('class_id', $classId)
        ->get();

    return response()->json($homework);
    }

}