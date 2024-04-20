<?php

use App\Http\Controllers\v1\NotificationsController;
use Illuminate\Support\Facades\Route;

Route::prefix('notifications')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [
            NotificationsController::class, 'index'
        ]);
        Route::get('/unread', [
            NotificationsController::class, 'getUnreadNotifications'
        ]);
        Route::post('/mark-read/{id}', [NotificationsController::class, 'markAsRead']);
        Route::post('/delete/{id}', [NotificationsController::class, 'delete']);
    });
});
