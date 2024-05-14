<?php

namespace App\Http\Controllers\Parent;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;
use App\Models\ParentStudentModel;
use Illuminate\Support\Facades\Validator;

class ParentGradeController extends Controller
{
    public function getParentStudents($userId)
    {
        try {
            $parentStudents = ParentStudentModel::where('parent_id', $userId)
                ->join('users', 'parent_student.student_id', '=', 'users.id')
                ->select('parent_student.id', 'parent_student.parent_id', 'parent_student.student_id', 'users.first_name', 'users.last_name')
                ->get();

            return response()->json($parentStudents);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch parent students', 'message' => $e->getMessage()], 500);
        }
    }

    public function getSelectedStudent($userId)
    {
        $student = User::findOrFail($userId);
        return response()->json($student);
    }

    public function getSelectedClass($classId)
    {
        $class = ClassModel::with('subjects', 'teachers')->findOrFail($classId);
        return response()->json($class);
    }

    public function getSelectedStudentGrades($studentId)
    {
        $grades = GradeModel::where('student_id', $studentId)->get();
        return response()->json($grades);
    }

}