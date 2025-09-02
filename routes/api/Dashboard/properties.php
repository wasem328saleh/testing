<?php
use App\Http\Controllers\Dashboard\PropertiesController;
use Illuminate\Support\Facades\Route;

Route::middleware('dashboard_localization')->prefix('properties')->group(function (){
    Route::prefix('dashboard')->group(function (){
        Route::prefix('room-type')->group(function (){
            Route::get('all', [PropertiesController::class, 'get_all_rooms_types']);
            Route::middleware('auth:api')->group(function (){
                Route::post('add', [PropertiesController::class, 'add_room_type']);
                Route::post('update', [PropertiesController::class, 'update_room_type']);
                Route::post('delete', [PropertiesController::class, 'delete_room_type']);
            });
        });

        Route::prefix('main-categories')->group(function (){
            Route::get('all', [PropertiesController::class, 'get_all_main_categories']);
            Route::middleware('auth:api')->group(function (){
                Route::post('add', [PropertiesController::class, 'add_main_category']);
                Route::post('update', [PropertiesController::class, 'update_main_category']);
                Route::post('delete', [PropertiesController::class, 'delete_main_category']);
            });
        });

        Route::prefix('sub-categories')->group(function (){
            Route::get('all/{id}', [PropertiesController::class, 'get_all_sub_categories_by_id_main_category']);
            Route::middleware('auth:api')->group(function (){
                Route::post('add', [PropertiesController::class, 'add_sub_category']);
                Route::post('update', [PropertiesController::class, 'update_sub_category']);
                Route::post('delete', [PropertiesController::class, 'delete_sub_category']);
            });
        });

        Route::prefix('categories')->group(function (){
            Route::get('all', [PropertiesController::class, 'get_all_categories']);
            Route::middleware('auth:api')->group(function (){

            });
        });

        Route::prefix('directions')->group(function (){
            Route::get('all', [PropertiesController::class, 'get_all_directions']);
            Route::middleware('auth:api')->group(function (){
                Route::post('add', [PropertiesController::class, 'add_direction']);
                Route::post('update', [PropertiesController::class, 'update_direction']);
                Route::post('delete', [PropertiesController::class, 'delete_direction']);
            });
        });

        Route::prefix('ownership_types')->group(function (){
            Route::get('all', [PropertiesController::class, 'get_all_ownership_types']);
            Route::middleware('auth:api')->group(function (){
                Route::post('add', [PropertiesController::class, 'add_ownership_type']);
                Route::post('update', [PropertiesController::class, 'update_ownership_type']);
                Route::post('delete', [PropertiesController::class, 'delete_ownership_type']);
            });
        });

        Route::prefix('pledge_types')->group(function (){
            Route::get('all', [PropertiesController::class, 'get_all_pledge_types']);
            Route::middleware('auth:api')->group(function (){
                Route::post('add', [PropertiesController::class, 'add_pledge_type']);
                Route::post('update', [PropertiesController::class, 'update_pledge_type']);
                Route::post('delete', [PropertiesController::class, 'delete_pledge_type']);
            });
        });

});
});
