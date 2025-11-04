<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'API ishlayapti!']);
});
Route::prefix('v1')->group(function () {
    // Ilmiy xodimlar ro‘yxati
    Route::get('/scientists', [\App\Http\Controllers\Api\ScientificController::class, 'index']);

    // Tanlangan xodimning maqolalari
    Route::get('/scientists/{id}/articles', [\App\Http\Controllers\Api\ScientificController::class, 'articles']);

    // Maqola haqida to‘liq ma’lumot
    Route::get('/articles/{id}', [\App\Http\Controllers\Api\ArticleApiController::class, 'show']);
});
