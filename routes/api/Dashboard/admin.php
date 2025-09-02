<?php
use App\Http\Controllers\Dashboard\AdminController;
use Illuminate\Support\Facades\Route;
Route::middleware('dashboard_localization')->group(function () {
    Route::prefix('admin')->group(function (){
        Route::prefix('dashboard')->group(function (){
            Route::middleware('auth:api')->group(function (){
                Route::get('all-users',[AdminController::class,'get_all_users']);
                Route::post('add-user',[AdminController::class,'add_user']);
                Route::post('edite-user',[AdminController::class,'edit_user']);
                Route::post('delete-user',[AdminController::class,'delete_user']);
                Route::post('change-status-user',[AdminController::class,'change_status_user']);
                Route::get('info-contact/all', [AdminController::class, 'get_all_system_contact_information']);
                Route::post('info-contact/add',[AdminController::class,'add_system_contact_information']);
                Route::post('info-contact/edit',[AdminController::class,'edit_system_contact_information']);
                Route::post('info-contact/delete',[AdminController::class,'delete_system_contact_information']);
                Route::post('send-notification',[AdminController::class,'send_notification']);
            });

        });
    });
});
