<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::resource('films', App\Http\Controllers\Api\FilmController::class)
    ->only(['index', 'store', 'update', 'destroy']);