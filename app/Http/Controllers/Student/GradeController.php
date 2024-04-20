<?php

namespace App\Http\Controllers\Student;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
    public function getStudent($userId)
    {
        $student = User::findOrFail($userId);
        return response()->json($student);
    }

    public function getClass($classId)
    {
        $class = ClassModel::with('subjects', 'teachers')->findOrFail($classId);
        return response()->json($class);
    }

    public function getStudentGrades($studentId)
    {
        $grades = GradeModel::where('student_id', $studentId)->get();
        return response()->json($grades);
    }

}