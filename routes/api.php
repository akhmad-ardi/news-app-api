<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewsController;

Route::get('/', function () {
    return 'Test';
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/news', [NewsController::class, 'get_news']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);

    Route::post('/news', [NewsController::class, 'create']);

    Route::put('/news/{slug}', [NewsController::class, 'update_news']);

    Route::put('/news/thumbnail/{slug}', [NewsController::class, 'update_thumbnail_news']);

    Route::put('/news/pictures/{slug}', [NewsController::class, 'update_pictures_news']);

    Route::delete('/news/{slug}', [NewsController::class, 'delete_news']);

    Route::prefix('/user')->group(function () {
        Route::get('', [UserController::class, 'get_user']);

        Route::get('/news', [UserController::class, 'get_user_news']);
    });
});
