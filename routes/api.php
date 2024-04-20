<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    require __DIR__ . '/v1/User.php';
    require __DIR__ . '/v1/Team.php';
    require __DIR__ . '/v1/Tag.php';
    require __DIR__ . '/v1/Manga.php';
    require __DIR__ . '/v1/MangaUnapproved.php';
    require __DIR__ . '/v1/Chapter.php';
    require __DIR__ . '/v1/Notification.php';
    require __DIR__ . '/v1/Search.php';
});
