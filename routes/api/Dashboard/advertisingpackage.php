<?php
use App\Http\Controllers\Dashboard\AdvertisingPackageController;
use Illuminate\Support\Facades\Route;

Route::middleware('dashboard_localization')->group(function () {
    Route::prefix('ad-package')->group(function (){
        Route::prefix('dashboard')->group(function (){
            Route::get('ad/getById/{id}',[AdvertisingPackageController::class,'getAdvertisementById']);
            Route::middleware('auth:api')->group(function (){
                Route::prefix('package')->group(function (){
                    Route::get('all',[AdvertisingPackageController::class,'get_all_advertising_packages']);
                    Route::post('add',[AdvertisingPackageController::class,'add_advertising_package']);
                    Route::post('update',[AdvertisingPackageController::class,'update_advertising_package']);
                    Route::post('delete',[AdvertisingPackageController::class,'delete_advertising_package']);
                });
                Route::prefix('ad')->group(function (){
                    Route::post('all',[AdvertisingPackageController::class,'get_all_advertising']);
//            Route::get('getById/{id}',[AdvertisingPackageController::class,'getAdvertisementById']);
                    Route::post('add',[AdvertisingPackageController::class,'add_advertising']);
                    Route::post('update',[AdvertisingPackageController::class,'update_advertising']);
                    Route::post('delete',[AdvertisingPackageController::class,'delete_advertising']);
                    Route::post('change-status',[AdvertisingPackageController::class,'change_status_advertising']);
                });
                Route::prefix('feature')->group(function (){
                    Route::get('all',[AdvertisingPackageController::class,'get_all_features']);
                    Route::post('add',[AdvertisingPackageController::class,'add_feature']);
                    Route::post('update',[AdvertisingPackageController::class,'update_feature']);
                    Route::post('delete',[AdvertisingPackageController::class,'delete_feature']);
                });
                Route::prefix('classification')->group(function (){
                    Route::get('all',[AdvertisingPackageController::class,'get_all_classifications']);
                    Route::post('add',[AdvertisingPackageController::class,'add_classification']);
                    Route::post('update',[AdvertisingPackageController::class,'update_classification']);
                    Route::post('delete',[AdvertisingPackageController::class,'delete_classification']);
                    Route::post('change-status',[AdvertisingPackageController::class,'change_status_classification']);
                });
            });

        });
    });
});
