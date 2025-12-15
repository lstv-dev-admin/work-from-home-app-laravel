<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
Route::post('/clear-tokens', [AuthController::class, 'clearAllTokens'])->name('clear-tokens'); // For testing only

// Simple test endpoint you can hit from Postman or any frontend
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/capture', [MainController::class, 'index']);

    // Userfile CRUD routes
    Route::apiResource('userfiles', UserfileController::class);
});
