<?php

use App\Http\Controllers\v1\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/search', SearchController::class);
