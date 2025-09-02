<?php
use App\Http\Controllers\Mobile\AddressController;
use Illuminate\Support\Facades\Route;

Route::middleware('localization')->prefix('address')->group(function (){
    Route::prefix('mobile')->group(function (){
        Route::get('all',[AddressController::class,'get_all_address_location']);
        Route::get('all-countries',[AddressController::class,'get_all_countries']);
        Route::get('all-cities-by/{country_id}',[AddressController::class,'get_all_cities_by_country_id']);
        Route::get('all-regions-by/{city_id}',[AddressController::class,'get_all_regions_by_city_id']);
    Route::middleware('auth:api')->group(function (){

    });

});
});
