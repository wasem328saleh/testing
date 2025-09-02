<?php
use App\Http\Controllers\Dashboard\ConfigController;
use Illuminate\Support\Facades\Route;

Route::middleware('dashboard_localization')->group(function () {
    Route::prefix('config')->group(function (){
        Route::prefix('dashboard')->group(function (){
            Route::get('get-classifications', [ConfigController::class, 'get_classifications']);
            Route::get('get-all-config', [ConfigController::class, 'get_all_config']);
            Route::post('get-config-attributes', [ConfigController::class, 'get_config_attributes']);
            Route::post('get-features', [ConfigController::class, 'get_features']);
            Route::get('get-all-languages', [ConfigController::class, 'get_languages']);
            Route::get('get-language-app', [ConfigController::class, 'get_language_app']);
            Route::post('change-language', [ConfigController::class, 'change_language']);
            Route::middleware('auth:api')->group(function (){
            });

        });
    });
});
