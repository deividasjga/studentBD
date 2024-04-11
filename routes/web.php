<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\StudentController;

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


Auth::routes();

Route::middleware(['auth'])->group(function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('api/students', [StudentController::class, 'index']);
    Route::post('api/students', [StudentController::class, 'store']);
    Route::put('api/students/{user}', [StudentController::class, 'update']);
    Route::delete('api/students/{user}', [StudentController::class, 'destroy']);
});

Route::middleware(['auth', 'user-role:admin'])->group(function(){
    // Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Route::get('api/users', [UserController::class, 'index']);
    // Route::post('api/users', [UserController::class, 'store']);
    // Route::get('/api/users/search', [UserController::class, 'search']);
    // Route::patch('/api/users/{user}/change-role', [UserController::class, 'changeRole']);
    // Route::put('api/users/{user}', [UserController::class, 'update']);
    // Route::delete('api/users', [UserController::class, 'bulkDelete']);
    // Route::delete('api/users/{user}', [UserController::class, 'destroy']);
});

Route::middleware(['auth', 'user-role:student'])->group(function(){
    
});

Route::middleware(['auth', 'user-role:teacher'])->group(function(){
    
});

Route::middleware(['auth', 'user-role:parent'])->group(function(){
    
});


Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');

