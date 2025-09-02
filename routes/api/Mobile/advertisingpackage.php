<?php
use App\Http\Controllers\Mobile\AdvertisingPackageController;
use Illuminate\Support\Facades\Route;

Route::middleware('localization')->prefix('ad-package')->group(function (){
    Route::prefix('mobile')->group(function (){
        Route::post('all-packages',[AdvertisingPackageController::class,'get_all_packages']);
        Route::post('get-all-advertising',[AdvertisingPackageController::class,'get_all_advertising'])
            ->middleware('AutoAuth');
        Route::post('get-all-advertising-sale',[AdvertisingPackageController::class,'get_all_advertising_sale'])
            ->middleware('AutoAuth');
        Route::post('get-all-advertising-rent',[AdvertisingPackageController::class,'get_all_advertising_rent'])
            ->middleware('AutoAuth');
        Route::post('search-serialNumber',[AdvertisingPackageController::class,'search_advertising_by_serialNumber'])
            ->middleware('AutoAuth');
        Route::post('filter-advertising',[AdvertisingPackageController::class,'search_filter_advertising']);
    Route::middleware('auth:api')->group(function (){
        Route::post('subscribe-in-package',[AdvertisingPackageController::class,'subscribe_in_package']);
        Route::post('add-advertising',[AdvertisingPackageController::class,'add_advertising']);
        Route::post('get-my-advertising',[AdvertisingPackageController::class,'get_my_advertising']);
        Route::post('edited-my-advertising',[AdvertisingPackageController::class,'edited_my_advertising']);
        Route::post('deleted-my-advertising',[AdvertisingPackageController::class,'deleted_my_advertising']);
        Route::post('edited-activation-advertising',[AdvertisingPackageController::class,'edited_activation_advertising']);
        Route::post('advertising-by-city',[AdvertisingPackageController::class,'search_advertising_by_cityId']);
        Route::post('my-subscriber',[AdvertisingPackageController::class,'get_my_subscriber']);
        Route::post('resubscribe',[AdvertisingPackageController::class,'resubscribe_advertising']);
        Route::post('rate-advertising',[AdvertisingPackageController::class,'rate_advertising']);
        Route::post('add-medias',[AdvertisingPackageController::class,'add_medias']);
        Route::post('delete-media',[AdvertisingPackageController::class,'delete_media']);
    });

});
});
