<?php
use App\Http\Controllers\Dashboard\AddressController;
use Illuminate\Support\Facades\Route;
Route::middleware('dashboard_localization')->group(function () {
    Route::prefix('address')->group(function (){
        Route::prefix('dashboard')->group(function (){
            Route::get('all',[AddressController::class,'get_all_address']);
            Route::get('all-cities-by/{country_id}',[AddressController::class,'get_all_cities_by_country_id']);
            Route::get('all-regions-by/{city_id}',[AddressController::class,'get_all_regions_by_city_id']);
            Route::prefix('country')->group(function (){
                Route::get('all',[AddressController::class,'get_all_countries']);

                Route::middleware('auth:api')->group(function (){
                    Route::post('add',[AddressController::class,'add_country']);
                    Route::post('update',[AddressController::class,'update_country']);
                    Route::post('delete',[AddressController::class,'delete_country']);
                });

            });

            Route::prefix('city')->group(function (){
                Route::get('all',[AddressController::class,'get_all_cities']);

                Route::middleware('auth:api')->group(function (){
                    Route::post('add',[AddressController::class,'add_city']);
                    Route::post('update',[AddressController::class,'update_city']);
                    Route::post('delete',[AddressController::class,'delete_city']);
                });

            });

            Route::prefix('region')->group(function (){
                Route::get('all',[AddressController::class,'get_all_regions']);

                Route::middleware('auth:api')->group(function (){
                    Route::post('add',[AddressController::class,'add_region']);
                    Route::post('update',[AddressController::class,'update_region']);
                    Route::post('delete',[AddressController::class,'delete_region']);
                });

            });

        });
    });
});
