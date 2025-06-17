<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Profile\PasswordController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(["middleware" => ["auth:api"]], function () {
    Route::get("profile", [AuthController::class, 'profile']);
    Route::get("logout", [AuthController::class, 'logout']);
    Route::get("refresh", [AuthController::class,'refreshToken']);
    Route::get('/verify-email', [AuthController::class, 'verify']);
    Route::post('/change_password', [PasswordController::class,'changeUserPassword']);
});

Route::post("register", [AuthController::class, 'register']);
Route::post("login", [AuthController::class, 'login']);
Route::post('auth/verify_user_email', [AuthController::class, 'verifyUserEmail']);
Route::post('auth/resend_email_velification', [AuthController::class, 'resendEmailVerificationLink']);