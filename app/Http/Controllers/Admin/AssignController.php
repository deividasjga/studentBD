<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;

class AssignController extends Controller
{
    public function teacherAssignList()
    {
        $teachers = User::where('role', 'teacher')->get();
        $teachers->load('teacherClasses');
        $teachers->load('teacherSubjects');
        
        return view('admin.teachers.indexAssign', compact('teachers'));
    }

    public function assignTeacherClasses($id)
    {
        $teacher = User::findOrFail($id);
        $classes = ClassModel::all();
        return view('admin.teachers.assignClass', compact('teacher', 'classes'));
    }

    public function updateTeacherClasses(Request $request, $id)
    {
        $teacher = User::findOrFail($id);
        $teacher->teacherClasses()->sync($request->input('classes', []));
        return redirect()->route('indexAssign')->with('success', 'Classes assigned to teacher successfully.');
    }

    public function assignTeacherSubjects(Request $request, $teacherId, $classId)
    {
        $teacher = User::findOrFail($teacherId);
        $class = ClassModel::findOrFail($classId);
        $assignedSubjects = $teacher->teacherSubjects()->wherePivot('class_id', $classId)->get();
        $allSubjects = $class->subjects()->get();
        return view('admin.teachers.assignSubject', compact('teacher', 'assignedSubjects', 'allSubjects', 'class'));
    }


    public function updateTeacherSubjects(Request $request, $teacherId, $classId)
{
    $teacher = User::findOrFail($teacherId);
    $subjects = $request->input('subjects', []);
    $teacher->teacherSubjects()->wherePivot('class_id', $classId)->detach();
    foreach ($subjects as $subjectId) {
        $teacher->teacherSubjects()->attach($subjectId, ['class_id' => $classId]);
    }

    return redirect()->route('indexAssign')->with('success', 'Subjects assigned to teacher successfully.');
}
}