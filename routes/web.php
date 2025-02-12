<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');

Route::post('/register', [AuthController::class, 'store'])->name('register.store');

Route::post('/task/store', [TaskController::class, 'store'])->name('task.store');

Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');

Route::delete('/task/{id}', [TaskController::class, 'destroy'])->name('task.destroy');

Route::put('/task/{id}', [TaskController::class, 'update'])->name('task.update');


