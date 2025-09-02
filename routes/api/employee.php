<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;


Route::middleware('check-active')->group(function (){
    Route::prefix('employee')->group(function (){
        Route::middleware('auth:api')->group(function (){
            Route::post('add-task',[EmployeeController::class,'add_task']);
            Route::get('get-all-my-tasks',[EmployeeController::class,'get_all_my_tasks']);
            Route::get('home-profile',[EmployeeController::class,'show_profile']);
        });
    });
});
