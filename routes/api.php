<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::match(['post', 'get'], 'login', [AuthController::class, 'login'])
    ->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('logout', [AuthController::class, 'logout']);

    Route::get('profile', [ProfileController::class, 'index']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
});
