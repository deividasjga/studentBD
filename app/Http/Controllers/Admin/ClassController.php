<?php

namespace App\Http\Controllers\Admin;

use App\Models\ClassModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::latest()->get();

        return $classes;
    }

    public function getClass($id)
    {
        $class = ClassModel::with('subjects')->find($id);
        return $class;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subjects' => 'array',
        ]);


        $class = ClassModel::create([
            'name' => $request->input('name'),
        ]);

        if ($request->has('subjects') && is_array($request->input('subjects'))) {
            $class->subjects()->attach($request->input('subjects'));
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
    

}