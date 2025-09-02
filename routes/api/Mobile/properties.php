<?php
use App\Http\Controllers\Mobile\PropertiesController;
use Illuminate\Support\Facades\Route;

Route::middleware('localization')->prefix('properties')->group(function (){
    Route::prefix('mobile')->group(function (){
        Route::get('get-all-directions', [PropertiesController::class, 'get_all_directions']);
        Route::get('get-all-rooms-types', [PropertiesController::class, 'get_all_rooms_types']);
        Route::get('get-all-main-categories', [PropertiesController::class, 'get_all_main_categories']);
        Route::get('get-all-sub-categories/{id}', [PropertiesController::class, 'get_all_sub_categories_by_id_main_category']);
        Route::get('get-all-categories', [PropertiesController::class, 'get_all_categories']);
        Route::get('get_all_ownership_types', [PropertiesController::class, 'get_all_ownership_types']);
        Route::get('get_all_pledge_types', [PropertiesController::class, 'get_all_pledge_types']);
    Route::middleware('auth:api')->group(function (){

    });

});
});
