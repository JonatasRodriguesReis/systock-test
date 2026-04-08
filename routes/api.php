<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me/produtos', [ProductController::class, 'myProducts']);

    Route::apiResource('usuarios', UserController::class);
    Route::apiResource('produtos', ProductController::class);

    Route::get('/relatorio-sql', [ ReportController::class, 'sqlReport' ] );
});
