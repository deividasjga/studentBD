<?php

namespace App\Http\Controllers\Teacher;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\SubjectModel;

class TeacherClassController extends Controller
{
    public function getTeacherClasses()
    {
        $teacher = auth()->user();
        $teacherClasses = $teacher->teacherClasses()->with('subjects')->get();
        return view('teacher.classes.teacherClassList', compact('teacherClasses'));
    }

    public function subjectGradeList($userId, $classId, $subjectId)
    {
        $user = User::findOrFail($userId);
        $class = ClassModel::findOrFail($classId);
        $subject = SubjectModel::findOrFail($subjectId);

        return view('teacher.classes.subjectGrading', compact('userId', 'classId', 'subjectId'));
    }
    
}