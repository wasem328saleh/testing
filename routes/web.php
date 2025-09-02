<?php

use App\Http\Controllers\GoogleLoginController;
use App\Models\MerchantRegisterOrder;
use App\Models\User;
use App\Services\ManageLangFiles;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Traits\GeneralTrait;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('testG',[\App\Http\Controllers\Controller::class,'testG']);
Route::get('tester',[\App\Http\Controllers\Controller::class,'tester']);

Route::get('form/generate-pdf',function (){

    return view('pdf');
});
Route::post('/generate-pdf', [\App\Http\Controllers\Controller::class,'generate'])->name('generate-pdf');

Route::get('trans_job',[\App\Http\Controllers\testController::class,'testT']);
Route::get('trans',function (){
    $localization=new GoogleTranslate();
    $localization->translate(strval($this->string));
    $lang=$localization->getLastDetectedSource();
    return $lang;
    User::findOrFail(2)->merchant_register_order()->create([
        'serial_number'=>'M_123456789',
        'date'=>Carbon::now()->toDate(),
        'status'=>'accept',
        'reply'=>'add from seeder'
    ]);
    return User::findOrFail(2)->merchant_register_order;
    $name='على الهيكل';
    $localization=new GoogleTranslate();
    $localization->translate($name);
    $lang=$localization->getLastDetectedSource();
    $languages=array_keys(config('environment_system.available_languages'));
    $languages= array_values(array_diff($languages,[$lang]));
    $trans=[];
    foreach ($languages as $language) {
        $tr=new GoogleTranslate();
        $tr->setSource($lang);
        $tr->setTarget($language);
        $value=$tr->translate($name);
        $trans[]=[$language=>$value];
        $tr->setTarget('en');
        $key=str_replace(' ','_',$tr->translate($name));
        $ml=new ManageLangFiles($language,'name');
        $ml->storeORupdate($key,$value);
    }
    return $trans;
    $tr=new GoogleTranslate();
    $tr->setSource();
    $tr->setTarget('tr');
    return $tr->translate('عقارات');
});


Route::get('/login/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/login/google/callback', [GoogleLoginController::class, 'handleProviderCallback']);

Route::get('testpermissionuser/{id}',[\App\Http\Controllers\Controller::class,'testpermissionuser']);

Route::get('t', [\App\Http\Controllers\Controller::class, 't']);
Route::get('tt', [\App\Http\Controllers\Controller::class, 'tt']);
Route::get('noti', [\App\Http\Controllers\Controller::class, 'noti']);
Route::get('users',function (){
    $users=User::all()->pluck('id')->toArray();
    return $users;
});

