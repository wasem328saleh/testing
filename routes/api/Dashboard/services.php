<?php

use App\Http\Controllers\Dashboard\OrdersController;
use App\Http\Controllers\Dashboard\ServicesController;
use Illuminate\Support\Facades\Route;

Route::middleware('dashboard_localization')->prefix('services')->group(function (){
    Route::prefix('dashboard')->group(function (){
        Route::prefix('services')->group(function (){
            Route::get('all',[ServicesController::class,'get_all_categories']);
            Route::get('all-with-providers',[ServicesController::class,'get_all_categories_services']);
            Route::middleware('auth:api')->group(function (){
                Route::post('add',[ServicesController::class,'add_categories_services']);
                Route::post('update',[ServicesController::class,'update_categories_services']);
                Route::post('delete',[ServicesController::class,'delete_categories_services']);
            });
        });

        Route::prefix('providers')->group(function (){
            Route::get('all/{id}',[ServicesController::class,'get_all_providers_by_service_id']);
            Route::middleware('auth:api')->group(function (){
                Route::post('add',[ServicesController::class,'add_providers_services']);
                Route::post('update',[ServicesController::class,'update_providers_services']);
                Route::post('delete',[ServicesController::class,'delete_providers_services']);
            });
        });

        Route::prefix('orders')->group(function (){
            Route::middleware('auth:api')->group(function (){
                Route::get('all',[ServicesController::class,'get_all_orders_services']);
                Route::post('accept',[ServicesController::class,'accept_order']);
                Route::post('cancel',[ServicesController::class,'cancel_order']);
            });
        });
});
});
