<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Simple test endpoint you can hit from Postman or any frontend
Route::get('/capture', [MainController::class, 'index']);
Route::get('/image', [MainController::class, 'showImage']);
