<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ParentStudentModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function index()
    {
        $users = User::with('students')->where('role', 'parent')->latest()->get();
        // $users = User::where('role', 'parent')->latest()->get();
        return $users;
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => 'parent',
            'address' => $request->input('address'),
            'date_of_birth' => $request->input('date_of_birth'),
            'gender' => $request->input('gender'),
        ]);

        return response()->json($user, 201);
    }

    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:8',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'gender' => 'nullable|in:male,female,other',
        ]);
    
        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : $user->password,
            'address' => $request->input('address'),
            'date_of_birth' => $request->input('date_of_birth'),
            'gender' => $request->input('gender'),
        ]);
    
        return response()->json($user, 200);
    }
    

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Parent deleted successfully'], 200);
    }



    public function assignParentStudents(Request $request, $selectedParentId)
    {
        $parent = User::findOrFail($selectedParentId);

        $validated = $request->validate([
            'students' => 'array',
            'students.*' => 'exists:users,id'
        ]);

        ParentStudentModel::where('parent_id', $parent->id)->delete();

        if (!empty($validated['students'])) {
            $parent->students()->sync($validated['students']);
        }

        $parent->load('students');

        return response()->json($parent->students);
    }
}