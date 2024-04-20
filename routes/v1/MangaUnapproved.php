<?php

use App\Http\Controllers\v1\MangaUnapprovedController;
use Illuminate\Support\Facades\Route;

Route::prefix('unapproved')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/{slug}', [MangaUnapprovedController::class, 'approve']);
        Route::put('/{slug}', [MangaUnapprovedController::class, 'update']);
        Route::delete('/{slug}', [MangaUnapprovedController::class, 'destroy']);

        Route::get('/', [MangaUnapprovedController::class, 'index']);

        Route::get(
            '/{slug}/team',
            [MangaUnapprovedController::class, 'getUnapprovedMangaTeam']
        );
        Route::get('/{slug}', [MangaUnapprovedController::class, 'show']);
    });
});
