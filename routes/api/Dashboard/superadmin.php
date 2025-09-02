<?php
use App\Http\Controllers\Dashboard\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware('dashboard_localization')->prefix('superadmin')->group(function (){
    Route::prefix('dashboard')->group(function (){
        Route::middleware('auth:api')->group(function (){
            Route::prefix('admin')->group(function (){
                Route::get('all',[SuperAdminController::class,'get_all_admins']);
                Route::post('add',[SuperAdminController::class,'add_admin']);
            });
            Route::prefix('user')->group(function (){
                Route::get('all',[SuperAdminController::class,'get_all_users']);
                Route::post('add',[SuperAdminController::class,'add_user']);
                Route::post('edite',[SuperAdminController::class,'edit_user']);
                Route::post('delete',[SuperAdminController::class,'delete_user']);
                Route::post('change-status',[SuperAdminController::class,'change_status_user']);
                Route::post('change-role',[SuperAdminController::class,'change_role_user']);
            });
            Route::prefix('role')->group(function (){
                Route::get('all',[SuperAdminController::class,'get_all_roles']);
                Route::post('update-permissions',[SuperAdminController::class,'edit_permissions_role']);
            });
            Route::prefix('permission')->group(function (){
                Route::get('all',[SuperAdminController::class,'get_all_permissions']);
            });
            Route::prefix('info-contact')->group(function (){
                Route::get('all',[SuperAdminController::class,'get_all_system_contact_information']);
                Route::post('add',[SuperAdminController::class,'add_system_contact_information']);
                Route::post('edit',[SuperAdminController::class,'edit_system_contact_information']);
                Route::post('delete',[SuperAdminController::class,'delete_system_contact_information']);
            });
            Route::prefix('notification')->group(function (){
                Route::post('send',[SuperAdminController::class,'send_notification']);
            });
    });

});
});
