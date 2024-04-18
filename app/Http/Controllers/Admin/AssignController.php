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
}