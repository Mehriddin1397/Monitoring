<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return view('admin.auth.login');
})->middleware('ip.restrict');

Route::get('/', function () {
    return view('admin.auth.login');
})->name('login.page');
Route::post('/',[\App\Http\Controllers\PageController::class,'login'])->name('login');
Route::post('/logout',[\App\Http\Controllers\PageController::class,'logout'])->name('logout');


Route::middleware(['auth','last.activity'])->prefix('admin')->group(function () {
    Route::get('/dashboard',[\App\Http\Controllers\PageController::class,'dashboard'])->name('dashboard');
    Route::resource('tasks', \App\Http\Controllers\TaskController::class)->except(['show']);

    Route::resource('users',\App\Http\Controllers\UserController::class);
    Route::get('/admin/projects/search', [\App\Http\Controllers\TaskController::class, 'search'])->name('projects.search');

    Route::get('/projects/{id}/file/{type}', [\App\Http\Controllers\PageController::class, 'showFile'])->name('projects.file');

    Route::post('/tasks/{task}/update-status', [\App\Http\Controllers\TaskController::class, 'updateStatus'])->name('updateStatus');

    Route::get('/auth-code', [\App\Http\Controllers\AuthCodeController::class, 'show'])->name('auth.code');
    Route::post('/auth-code', [\App\Http\Controllers\AuthCodeController::class, 'verify'])->name('auth.code.verify');

    Route::post('/file_upload', [\App\Http\Controllers\TaskController::class, 'uploadfile'])->name('file.upload');

    Route::get('/tasks/status/{status}', [\App\Http\Controllers\TaskController::class, 'statusFilter'])->name('tasks.status');
    Route::get('/tasks/failed', [\App\Http\Controllers\TaskController::class, 'failedTasks'])->name('tasks.failed');

    Route::get('/tasks/completed', [\App\Http\Controllers\TaskController::class, 'completed'])->name('tasks.completed');
    Route::get('/monitoring/umumiy', [\App\Http\Controllers\TaskController::class, 'umumiyStatistika'])->name('monitoring.umumiy');





});
