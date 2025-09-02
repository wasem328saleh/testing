<?php
use App\Http\Controllers\Dashboard\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::middleware('dashboard_localization')->prefix('statistics')->group(function (){
    Route::prefix('dashboard')->group(function (){
        Route::middleware('auth:api')->group(function (){

    });

});
});
