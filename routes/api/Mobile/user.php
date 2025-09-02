<?php
use App\Http\Controllers\Mobile\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('localization')->prefix('user')->group(function (){
    Route::prefix('mobile')->group(function (){
        Route::post('get-notification-device',[UserController::class,'get_my_notifications_by_device_token']);
        Route::get('get-system-contact-information', [UserController::class, 'get_system_contact_information']);
        Route::post('get-user-profile',[UserController::class,'get_user_profile']);
    Route::middleware('auth:api')->group(function (){
        Route::get('my-notifications',[UserController::class,'get_my_notifications']);
        Route::post('read-notifications',[UserController::class,'read_notifications']);
        Route::post('update-my-info',[UserController::class,'update_my_info']);
        Route::post('add-favourite',[UserController::class,'add_to_favourites']);
        Route::get('get-favourites',[UserController::class,'get_my_favourites']);
        Route::post('delete-favourite',[UserController::class,'delete_from_my_favourites']);
        Route::post('delete-my-account',[UserController::class,'delete_my_account']);

    });
});
});
