<?php

namespace App\Http\Controllers\Teacher;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;
use App\Models\HomeworkModel;

class TeacherHomeworkController extends Controller
{
    public function index($userId)
    {
        $teacher = User::findOrFail($userId);

        $homework = HomeworkModel::with('teacher', 'class', 'subject')
            ->where('teacher_id', $teacher->id)
            ->get();

        return response()->json($homework);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'end_date' => 'required|date',
            'teacher_id' => 'required|integer',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ]);

        $homework = HomeworkModel::create($request->all());
        return response()->json($homework, 201);
    }

    public function update(Request $request, $id)
    {
        $homework = HomeworkModel::findOrFail($id);
        
        $validatedData = $request->validate([
            'description' => 'required|string',
            'end_date' => 'required|date',
            'teacher_id' => 'required|integer',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ]);

        $homework->update($request->all());

        return response()->json($homework, 200);
    }

    public function destroy($id)
    {
        $homework = HomeworkModel::findOrFail($id);
        $homework->delete();

        return response()->json(['message' => 'Homework deleted successfully'], 200);
    }
}