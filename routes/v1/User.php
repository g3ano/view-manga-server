<?php

use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::get('/auth/check', [UserController::class, 'chekcAuth']);
    Route::get('/search', [UserController::class, 'search']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/{slug}/role', [UserController::class, 'toggleRole']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::get('/{slug}', [UserController::class, 'show']);
    Route::get('/{slug}/teams', [UserController::class, 'getUserTeams']);
});
