<?php

namespace App\Http\Controllers\Admin;

use App\Models\SubjectModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = SubjectModel::latest()->get();
        return $subjects;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
    
        $subject = SubjectModel::create([
            'name' => $request->input('name'),
        ]);
    
        return response()->json($subject, 201);
    }

    public function update(SubjectModel $subject)
    {
        request()->validate([
            'name' => 'required|unique:subject,name,'.$subject->id,
        ]);

        $subject -> update([
            'name' => request('name'),
        ]);

        return $subject;
    }


    public function destroy($id)
    {
        $subject = SubjectModel::findOrFail($id);
        $subject->delete();
        return response()->json(['message' => 'Subject deleted successfully'], 200);
    }

}