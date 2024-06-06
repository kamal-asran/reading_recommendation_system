<?php

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->prefix('books')->group(function () {

    Route::post('/reading-intervals', [BookController::class, 'storeIntervals']);
    Route::get('/recommendations', [BookController::class, 'recommendations']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::resource('books', BookController::class);
});