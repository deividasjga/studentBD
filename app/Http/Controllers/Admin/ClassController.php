<?php

namespace App\Http\Controllers\Admin;

use App\Models\ClassModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with('subjects')->latest()->get();
        return $classes;
    }

    public function getClass($id)
    {
        $class = ClassModel::with('subjects')->find($id);
        return $class;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:class|max:255',
            'subjects' => 'array',
        ]);

        $class = ClassModel::create([
            'name' => $validatedData['name'],
        ]);

        if (isset($validatedData['subjects'])) {
            $class->subjects()->attach($validatedData['subjects']);
        }

        return response()->json($class, 201);
    }


    public function update(Request $request, ClassModel $class)
    {
        $request->validate([
            'name' => 'required|unique:class,name,'.$class->id,
            'subjects' => 'array',
        ]);
    
        $class->update([
            'name' => request('name'),
        ]);
    
        if ($request->has('subjects')) {
            $class->subjects()->sync($request->input('subjects'));
        } else {
            $class->subjects()->detach();
        }
    
        return response()->json($class, 200);
    }


    public function destroy($id)
    {
        try {
            $class = ClassModel::findOrFail($id);
            $class->delete();
            
            return response()->json(['message' => 'Class deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete class'], 500);
        }
    }
    
}