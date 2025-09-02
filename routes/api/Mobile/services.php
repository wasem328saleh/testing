<?php
use App\Http\Controllers\Mobile\ServicesController;
use Illuminate\Support\Facades\Route;

Route::middleware('localization')->prefix('services')->group(function (){
    Route::prefix('mobile')->group(function (){
        Route::get('categories-services',[ServicesController::class,'get_all_categories_services']);
        Route::get('categories-with-providers',[ServicesController::class,'get_all_categories_services_with_service_providers']);
        Route::post('providers-by',[ServicesController::class,'get_service_providers_by_category_id']);
    Route::middleware('auth:api')->group(function (){
        Route::post('send-order',[ServicesController::class,'send_order_add_service_provider']);
        Route::post('my-orders-providers',[ServicesController::class,'get_my_orders_add_service_provider']);
        Route::post('edite',[ServicesController::class,'edited_my_service_provider']);
        Route::post('delete',[ServicesController::class,'deleted_my_service_provider']);
        Route::post('activation-edite',[ServicesController::class,'edited_activation_my_service_provider']);
        Route::post('rate-provider',[ServicesController::class,'rate_service_provider']);
    });

});
});
