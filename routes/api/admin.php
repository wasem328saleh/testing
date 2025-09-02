<?php


use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function (){
    Route::middleware('auth:api')->group(function (){
Route::post('add-employee',[AdminController::class,'add_employee']);
Route::post('delete-employee',[AdminController::class,'delete_employee']);
Route::post('edit-employee',[AdminController::class,'edit_employee']);
Route::get('get-all-employee',[AdminController::class,'get_all_employee']);
Route::post('show-employee',[AdminController::class,'show_employee']);
//Route::post('get_all-tasks-employee',[AdminController::class,'get_all_tasks_employee']);
Route::post('active-not-active',[AdminController::class,'active_or_not_employee']);
Route::post('payment',[AdminController::class,'payment_to_employee']);
Route::post('edit-cost-per-hour',[AdminController::class,'edit_cost_per_hour']);
Route::get('get-cost-per-hour',[AdminController::class,'get_cost_per_hour']);
    });

    Route::get('forget-password',[AdminController::class,'forget_Password']);
});


