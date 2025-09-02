<?php
use App\Http\Controllers\Dashboard\ComplaintsController;
use Illuminate\Support\Facades\Route;

Route::middleware('dashboard_localization')->group(function () {
    Route::prefix('complaints')->group(function (){
        Route::prefix('dashboard')->group(function (){
            Route::middleware('auth:api')->group(function (){
                Route::get('all', [ComplaintsController::class, 'get_all_complaints']);
                Route::post('add-reply', [ComplaintsController::class, 'add_complaint_response']);
            });
        });
    });
});


