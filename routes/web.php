<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.auth.login');
})->name('login');
Route::post('/',[\App\Http\Controllers\PageController::class,'login'])->name('login');
Route::post('/logout',[\App\Http\Controllers\PageController::class,'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard',[\App\Http\Controllers\PageController::class,'dashboard'])->name('dashboard');
    Route::resource('projects',\App\Http\Controllers\TaskController::class);
    Route::resource('users',\App\Http\Controllers\UserController::class);
    Route::get('/admin/projects/search', [\App\Http\Controllers\TaskController::class, 'search'])->name('projects.search');

    Route::get('/projects/{id}/file/{type}', [\App\Http\Controllers\PageController::class, 'showFile'])->name('projects.file');



});
