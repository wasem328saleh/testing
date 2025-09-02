<?php
use App\Http\Controllers\Mobile\ComplaintsController;
use Illuminate\Support\Facades\Route;

Route::middleware('localization')->prefix('complaints')->group(function (){
    Route::prefix('mobile')->group(function (){
    Route::middleware('auth:api')->group(function (){
        Route::post('send',[ComplaintsController::class,'send_complaint']);
        Route::get('my-complaints', [ComplaintsController::class, 'get_my_complaints']);
    });

});
});
