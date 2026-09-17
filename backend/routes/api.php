<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ReportController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/candidates', [CandidateController::class, 'index']);
    Route::post('/votes', [VoteController::class, 'store']);

    Route::middleware('admin')->group(function () {
        Route::apiResource('/admin/users', UserController::class);
        Route::post('/admin/users/{user}/reset-vote', [UserController::class, 'resetVote']);
        Route::apiResource('/admin/candidates', CandidateController::class)->except(['index']);
        Route::get('/admin/ranking', [ReportController::class, 'ranking']);
    });
});
