<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Profile\PasswordController;




Route::group(["middleware" => ["auth:api"]], function () {
    Route::get("auth/profile", [AuthController::class, 'profile']);
    Route::get("auth/logout", [AuthController::class, 'logout']);
    Route::get("refresh", [AuthController::class,'refreshToken']);
    Route::get('/verify-email', [AuthController::class, 'verify']);
    Route::post('/change_password', [PasswordController::class,'changeUserPassword']);
});

Route::post("register", [AuthController::class, 'register']);
Route::post("login", [AuthController::class, 'login']);
Route::post('auth/verify_user_email', [AuthController::class, 'verifyUserEmail']);
Route::post('auth/resend_email_velification', [AuthController::class, 'resendEmailVerificationLink']);