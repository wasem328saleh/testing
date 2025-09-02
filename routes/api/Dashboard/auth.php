<?php
use App\Http\Controllers\Dashboard\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('dashboard_localization')->group(function () {
    Route::prefix('auth')->group(function (){
        Route::prefix('dashboard')->group(function (){
            Route::post('login', [AuthController::class, 'login'])->middleware('verified_api');
            Route::post('forget_password', [AuthController::class, 'forget_Password']);
            Route::post('verify_reset_password', [AuthController::class, 'verify_reset_password']);
            Route::post('verify_reset_password_b', [AuthController::class, 'verify_reset_password_b']);
            Route::post('resend_verify_reset_password', [AuthController::class, 'resend_verify_reset_password']);
            Route::middleware('auth:api')->group(function () {
                Route::get('user-info', [AuthController::class, 'userInfo']);
                Route::post('logout', [AuthController::class, 'logout']);
            });
        });

    });
});
