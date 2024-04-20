<?php

use App\Http\Controllers\v1\ChapterController;
use Illuminate\Support\Facades\Route;

Route::prefix('chapters')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [ChapterController::class, 'index']);
        Route::post('/', [ChapterController::class, 'store']);
    });

    Route::get('/team/{slug}', [ChapterController::class, 'getTeamChapters']);

    Route::get('/team/{slug}/{id}', [ChapterController::class, 'show']);
});
