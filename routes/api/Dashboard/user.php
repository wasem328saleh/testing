<?php
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('dashboard_localization')->prefix('user')->group(function (){
    Route::prefix('dashboard')->group(function (){
        Route::middleware('auth:api')->group(function (){
            Route::post('update-my-info',[UserController::class,'update_my_info']);
            Route::get('my-notifications',[UserController::class,'get_my_notifications']);
            Route::post('get-user-profile',[UserController::class,'get_user_profile']);
        });

});
});
