<?php


use App\Http\Controllers\Dashboard\MerchantRegisterOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('dashboard_localization')->group(function () {
    Route::prefix('MerchantsOrders')->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::post('/', [MerchantRegisterOrderController::class, 'get_all_orders']);
            Route::middleware('auth:api')->group(function () {
                Route::post('accept', [MerchantRegisterOrderController::class, 'accept_order']);
                Route::post('cancel', [MerchantRegisterOrderController::class, 'cancel_order']);
            });
        });
    });
});


