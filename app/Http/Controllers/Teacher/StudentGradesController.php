<?php

namespace App\Http\Controllers\Teacher;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;
use Illuminate\Support\Facades\Validator;

class StudentGradesController extends Controller
{
    public function getStudents($classId, $subjectId)
    {
        $students = User::where('student_class_id', $classId)->get();
        return response()->json($students);
    }

    public function getStudentsGrades($classId, $subjectId)
    {
        $grades = GradeModel::where('class_id', $classId)
                            ->where('subject_id', $subjectId)
                            ->get();

        if ($grades->isEmpty()){
            return response()->json(['message' => 'No grades found for the specified class and subject.'], 404);
        }

        return response()->json($grades);
    }
}