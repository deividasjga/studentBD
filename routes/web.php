<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ParentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
})->name('test');

Route::get('/StudentList', function () {
    return view('admin.students.StudentList');
})->name('StudentList');

Route::get('/TeacherList', function () {
    return view('admin.teachers.TeacherList');
})->name('TeacherList');

Route::get('/ParentList', function () {
    return view('admin.parents.ParentList');
})->name('ParentList');

Route::get('/subjects', function () {
    return view('admin.subjects.subjectList');
})->name('subjectList');

Route::get('/classes', function () {
    return view('admin.classes.classList');
})->name('classList');


Auth::routes();

// Route::middleware(['auth'])->group(function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('api/students', [StudentController::class, 'index']);
    Route::post('api/students', [StudentController::class, 'store']);
    Route::put('api/students/{user}', [StudentController::class, 'update']);
    Route::delete('api/students/{user}', [StudentController::class, 'destroy']);

    Route::get('api/teachers', [TeacherController::class, 'index']);
    Route::post('api/teachers', [TeacherController::class, 'store']);
    Route::put('api/teachers/{user}', [TeacherController::class, 'update']);
    Route::delete('api/teachers/{user}', [TeacherController::class, 'destroy']);

    Route::get('api/parents', [ParentController::class, 'index']);
    Route::post('api/parents', [ParentController::class, 'store']);
    Route::put('api/parents/{user}', [ParentController::class, 'update']);
    Route::delete('api/parents/{user}', [ParentController::class, 'destroy']);

    Route::get('api/subjects', [SubjectController::class, 'index']);
    Route::post('api/subjects', [SubjectController::class, 'store']);
    Route::put('api/subjects/{subject}', [SubjectController::class, 'update']);
    Route::delete('api/subjects/{subject}', [SubjectController::class, 'destroy']);

    Route::get('api/classes', [ClassController::class, 'index']);
    Route::get('api/classes/{class}', [ClassController::class, 'show'])->name('classes.show');
    Route::post('api/classes', [ClassController::class, 'store']);
    Route::put('api/classes/{class}', [ClassController::class, 'update']);
    Route::delete('api/classes/{class}', [ClassController::class, 'destroy']);
    Route::get('api/classes/getClass/{id}', [ClassController::class, 'getClass']);
// });

Route::middleware(['auth', 'user-role:admin'])->group(function(){
    // Route::get('/home', [HomeController::class, 'index'])->name('home');
});

Route::middleware(['auth', 'user-role:student'])->group(function(){
    
});

Route::middleware(['auth', 'user-role:teacher'])->group(function(){
    
});

Route::middleware(['auth', 'user-role:parent'])->group(function(){
    
});


Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');

