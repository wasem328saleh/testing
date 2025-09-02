<?php
use App\Http\Controllers\Mobile\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('localization')->prefix('auth')->group(function (){
    Route::prefix('mobile')->group(function (){
        Route::post('register', [AuthController::class, 'register']);
        Route::post('verify',[AuthController::class,'verify']);
        Route::post('resend_verify',[AuthController::class,'resend_verify']);
        Route::post('login', [AuthController::class, 'login'])->middleware('verified_api');
        Route::post('forget_password', [AuthController::class, 'forget_Password']);
        Route::post('verify_reset_password', [AuthController::class, 'verify_reset_password']);
        Route::post('verify_reset_password_b', [AuthController::class, 'verify_reset_password_b']);
        Route::post('resend_verify_reset_password', [AuthController::class, 'resend_verify_reset_password']);
        Route::middleware('auth:api')->group(function () {
            Route::get('user-info', [AuthController::class, 'userInfo']);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('verify_new_email', [AuthController::class, 'verify_update_email']);
        });
    });
});
