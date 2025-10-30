<?php

use Illuminate\Support\Facades\Route;

Route::get('/tes/boss', function () {
    return view('admin.auth.login');
});

Route::get('/', function () {
    return view('admin.auth.login');
})->name('login.page')->middleware('ip.restrict');;
Route::post('/',[\App\Http\Controllers\PageController::class,'login'])->name('login');
Route::post('/logout',[\App\Http\Controllers\PageController::class,'logout'])->name('logout');


Route::middleware(['auth','last.activity'])->prefix('admin')->group(function () {
    Route::get('/dashboard',[\App\Http\Controllers\PageController::class,'dashboard'])->name('dashboard');
    Route::resource('tasks', \App\Http\Controllers\TaskController::class)->except(['show']);

    Route::resource('users',\App\Http\Controllers\UserController::class);
    Route::get('/admin/project/search', [\App\Http\Controllers\TaskController::class, 'search'])->name('search');

    Route::resource('documents', \App\Http\Controllers\DocumentController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('articles', \App\Http\Controllers\ArticleController::class);
    Route::resource('participants', \App\Http\Controllers\ParticipantController::class);

    Route::resource('ongoing-works', \App\Http\Controllers\OngoingWorkController::class);
    Route::resource('planned-works', \App\Http\Controllers\PlannedWorkController::class);
    Route::resource('completed-works', \App\Http\Controllers\CompletedWorkController::class);
    Route::resource('suggestions', \App\Http\Controllers\SuggestionController::class);


    Route::get('/category/{id}/documents', [\App\Http\Controllers\DocumentController::class, 'showByCategory'])->name('documents.byCategory');


    Route::get('/project/{id}/file/{type}', [\App\Http\Controllers\PageController::class, 'showFile'])->name('project.file');

    Route::get('/document/{id}', [\App\Http\Controllers\DocumentController::class, 'showFile'])->name('document.file');

    Route::post('/tasks/{task}/update-status', [\App\Http\Controllers\TaskController::class, 'updateStatus'])->name('updateStatus');

    Route::get('/auth-code', [\App\Http\Controllers\AuthCodeController::class, 'show'])->name('auth.code');
    Route::post('/auth-code', [\App\Http\Controllers\AuthCodeController::class, 'verify'])->name('auth.code.verify');

    Route::post('/file_upload', [\App\Http\Controllers\TaskController::class, 'uploadfile'])->name('file.upload');

    Route::get('/tasks/status/{status}', [\App\Http\Controllers\TaskController::class, 'statusFilter'])->name('tasks.status');
    Route::get('/tasks/failed', [\App\Http\Controllers\TaskController::class, 'failedTasks'])->name('tasks.failed');

    Route::get('/tasks/completed', [\App\Http\Controllers\TaskController::class, 'completed'])->name('tasks.completed');
    Route::get('/monitoring/umumiy', [\App\Http\Controllers\TaskController::class, 'umumiyStatistika'])->name('monitoring.umumiy');
    Route::get('/monitoring/hisobot', [\App\Http\Controllers\PageController::class, 'hisobot'])->name('monitoring.hisobot');

    Route::resource('projects',\App\Http\Controllers\ProjectController::class);
    Route::get('/admin/projects/search', [\App\Http\Controllers\ProjectController::class, 'search'])->name('projects.search');


    Route::get('/projects/{id}/file/{type}', [\App\Http\Controllers\PageController::class, 'showFilee'])->name('projects_file');

    Route::post('/pro_documents/{project}', [\App\Http\Controllers\Pro_documentController::class, 'store'])->name('pro_document.store');

    Route::post('/articles/check', [\App\Http\Controllers\ArticleController::class, 'check'])->name('articles.check');




});
