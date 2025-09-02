<?php
use App\Http\Controllers\Dashboard\OrdersController;
use Illuminate\Support\Facades\Route;

Route::middleware('dashboard_localization')->prefix('orders')->group(function (){
    Route::prefix('dashboard')->group(function (){
        Route::middleware('auth:api')->group(function (){
       Route::post('/', [OrdersController::class, 'get_all_orders']);
       Route::post('by-classification', [OrdersController::class, 'get_all_ordersBy']);
       Route::post('accept', [OrdersController::class, 'accept_order']);
       Route::post('cancel', [OrdersController::class, 'cancel_order']);
    });

});
});
