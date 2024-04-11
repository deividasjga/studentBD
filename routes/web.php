<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\HomeController;

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
})->name('test')->middleware('auth');

Auth::routes();


Route::middleware(['auth'])->group(function(){
    // Route::get('/home', [HomeController::class, 'index'])->name('home');
});

Route::middleware(['auth', 'user-role:admin'])->group(function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

Route::middleware(['auth', 'user-role:student'])->group(function(){
    
});

Route::middleware(['auth', 'user-role:teacher'])->group(function(){
    
});

Route::middleware(['auth', 'user-role:parent'])->group(function(){
    
});


Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');

