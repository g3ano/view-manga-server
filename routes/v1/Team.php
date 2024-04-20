<?php

use App\Http\Controllers\v1\TeamController;
use Illuminate\Support\Facades\Route;

Route::prefix('teams')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [TeamController::class, 'store']);

        Route::prefix('join-request')->group(function () {
            Route::post('/{slug}/accept', [TeamController::class, 'acceptJoinRequest']);
            Route::post('/{slug}/refuse', [TeamController::class, 'refuseJoinRequest']);
            Route::post('/{slug}', [TeamController::class, 'joinTeamRequest']);
        });

        Route::delete('/{slug}', [TeamController::class, 'destroy']);
        Route::put('/{slug}', [TeamController::class, 'update']);
    });

    Route::get('/', [TeamController::class, 'index']);
    Route::get('/{slug}', [TeamController::class, 'show']);
    Route::get('/search', [TeamController::class, 'search']);
    Route::get('/{slug}/mangas', [TeamController::class, 'getTeamMangas']);
    Route::get('/{slug}/mangas/unapproved', [
        TeamController::class, 'getTeamUnapprovedMangas'
    ]);
    Route::get('/{slug}/members', [
        TeamController::class, 'getTeamMembers'
    ]);
    Route::get('/{slug}/members/pending', [
        TeamController::class, 'getTeamPendingMembers'
    ]);
});
