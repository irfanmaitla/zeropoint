<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API
|
*/

Route::middleware(['auth:sanctum', 'verified'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/user-login', [AuthController::class, 'loginUser']);

Route::post('auth/send-verification-email', [AuthController::class, 'sendVerificationEmail'])->name('verification.send')->middleware('auth:sanctum');
Route::post('verify-email/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');
Route::get('auth/verify', [AuthController::class, 'verificationNotice'])->name('verification.notice')->middleware('auth:sanctum');

Route::middleware(['auth:sanctum','verified'])->group(function () {
    Route::post('create-user', [UserController::class, 'createUser']);
    Route::get('users', [UserController::class, 'getAllUsers']);
    Route::get('user/{user_id}', [UserController::class, 'getUser']);
    Route::post('delete-user/{user_id}', [UserController::class, 'deleteUser']);

    Route::apiResource('tasks', TaskController::class);
});
