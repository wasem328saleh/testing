<?php

use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
require __DIR__ . '/api/auth.php';
require __DIR__ . '/api/admin.php';
require __DIR__ . '/api/employee.php';


require __DIR__ . '/api/Dashboard/address.php';
require __DIR__ . '/api/Dashboard/admin.php';
require __DIR__ . '/api/Dashboard/advertisingpackage.php';
require __DIR__ . '/api/Dashboard/auth.php';
require __DIR__ . '/api/Dashboard/complaints.php';
require __DIR__ . '/api/Dashboard/config.php';
require __DIR__ . '/api/Dashboard/orders.php';
require __DIR__ . '/api/Dashboard/properties.php';
require __DIR__ . '/api/Dashboard/services.php';
require __DIR__ . '/api/Dashboard/statistics.php';
require __DIR__ . '/api/Dashboard/superadmin.php';
require __DIR__ . '/api/Dashboard/user.php';
require __DIR__ . '/api/Dashboard/merchant_register_orders.php';



require __DIR__ . '/api/Mobile/address.php';
require __DIR__ . '/api/Mobile/advertisingpackage.php';
require __DIR__ . '/api/Mobile/auth.php';
require __DIR__ . '/api/Mobile/complaints.php';
require __DIR__ . '/api/Mobile/config.php';
require __DIR__ . '/api/Mobile/properties.php';
require __DIR__ . '/api/Mobile/services.php';
require __DIR__ . '/api/Mobile/user.php';



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('testauth',[\App\Http\Controllers\Controller::class,'testauth'])->middleware('AutoAuth');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('permissions',\App\Http\Controllers\PermissionController::class);
    Route::apiResource('roles',\App\Http\Controllers\RoleController::class);
    Route::apiResource('users',\App\Http\Controllers\UserController::class);
    Route::get('test_one_gate',[UserController::class,'test_one_gate']);
    Route::get('test_tow_gate',[UserController::class,'test_tow_gate']);
});

Route::post('/upload', [\App\Http\Controllers\Controller::class,'uploadFile']);

//Route::get('redirectToProvider',[GoogleLoginController::class,'redirectToProvider']);
//Route::post('/login/google/callback',[GoogleLoginController::class,'handleProviderCallback']);

Route::get('/login/google', [GoogleLoginController::class, 'redirectToProvider'])->name('login.google');
Route::get('/oauth2callback', [GoogleLoginController::class, 'handleProviderCallback'])->name('oauth.callback');
Route::post('/login/google/callback', [GoogleLoginController::class, 'handleProviderCallbackApi'])->name('handleProviderCallbackApi');


Route::post('test-request',[\App\Http\Controllers\Mobile\AdvertisingPackageController::class,'add_advertising'])->middleware('auth:api');
Route::post('test-get-rules-request',[\App\Http\Controllers\Controller::class,'test_rule'])
 ->middleware('auth:api');

