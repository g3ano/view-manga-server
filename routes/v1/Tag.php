<?php

use App\Http\Controllers\v1\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('tags')->group(function () {
    Route::middleware(['guest:sanctum'])->group(function () {
        Route::get('/', [TagController::class, 'index']);
        Route::get('/{id}', [TagController::class, 'show']);
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/', [TagController::class, 'store']);
        Route::put('/{id}', [TagController::class, 'update']);
        Route::Delete('/{id}', [TagController::class, 'destroy']);
    });
});
