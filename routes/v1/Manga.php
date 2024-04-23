<?php

use App\Http\Controllers\v1\MangaController;
use Illuminate\Support\Facades\Route;

Route::prefix('mangas')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/', [MangaController::class, 'store']);
        Route::put('/', [MangaController::class, 'update']);
        Route::delete('/{slug}', [MangaController::class, 'destroy']);

        require __DIR__ . '/MangaUnapproved.php';
    });

    Route::get('/', [MangaController::class, 'index']);
    Route::get('/latest', [MangaController::class, 'latest']);
    Route::get('/{slug}/team', [MangaController::class, 'getMangaTeam']);
    Route::get('/{slug}/chapters', [MangaController::class, 'getMangaChapters']);
    Route::get('/{slug}', [MangaController::class, 'show']);
});
