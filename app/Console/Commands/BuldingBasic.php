<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BuldingBasic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:building';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $path_models_name='models.name';
    protected $path_models_operations='models.operations';


    /**
     * Execute the console command.
     */
    public function handle()
    {
//        $models=config($this->path_models_name);
//        $operations=config($this->path_models_operations);
////        exec('php artisan make:model '.$models[0].' -'.$operations);
////        $this->call('make:model', ['name'=>$models[0],'migration']);
//        foreach ($models as $model) {
////            $output='';
//            exec('php artisan make:model '.$model.' -'.$operations);
//
//            sleep(1);
//            $this->info($model."  Done ...");
//        }


//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*

//        $folderDashboard=base_path('app/Repositories/Dashboard');
//        $filesDashboard=File::directories($folderDashboard);
//
//        foreach ($filesDashboard as $file) {
//            $name=Str::after($file, 'Dashboard\\');
//            exec('php artisan make:controller Dashboard\\'.$name.'Controller');
//            $this->info($name."  Done ...");
//        }
//
//        $this->info('----------------------------------');
//        $this->info('----------------------------------');
//        $folderMobile=base_path('app/Repositories/Mobile');
//        $filesMobile=File::directories($folderMobile);
//
//        foreach ($filesMobile as $file) {
//            $name=Str::after($file, 'Mobile\\');
//            exec('php artisan make:controller Mobile\\'.$name.'Controller');
//            $this->info($name."  Done ...");
//        }


//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*


//                $folderDashboard=base_path('app/Repositories/Dashboard');
//        $filesDashboard=File::directories($folderDashboard);
//
//        foreach ($filesDashboard as $file) {
//            $name=Str::after($file, 'Dashboard\\');
//
//            $content="<?php
//use App\\Http\\Controllers\\Dashboard\\".$name."Controller;
//use Illuminate\Support\Facades\Route;
//
//Route::prefix('".Str::lower($name)."')->group(function (){
//    Route::middleware('auth:api')->group(function (){
//
//    });
//
//});";
//            file_put_contents(base_path('routes/api/Dashboard/'.Str::lower($name).'.php'),$content);
//            $this->info($name."  Done ...");
//        }
//
//        $this->info('----------------------------------');
//        $this->info('----------------------------------');
//        $folderMobile=base_path('app/Repositories/Mobile');
//        $filesMobile=File::directories($folderMobile);
//
//        foreach ($filesMobile as $file) {
//            $name=Str::after($file, 'Mobile\\');
//
//            $content="<?php
//use App\\Http\\Controllers\\Mobile\\".$name."Controller;
//use Illuminate\Support\Facades\Route;
//
//Route::prefix('".Str::lower($name)."')->group(function (){
//    Route::middleware('auth:api')->group(function (){
//
//    });
//
//});";
//
//            file_put_contents(base_path('routes/api/Mobile/'.Str::lower($name).'.php'),$content);
//            $this->info($name."  Done ...");
//        }



//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*


//                        $folderDashboard=base_path('app/Repositories/Dashboard');
//        $filesDashboard=File::directories($folderDashboard);
//        foreach ($filesDashboard as $file) {
//                        $name=Str::after($file, 'Dashboard\\');
//            $this->info("require __DIR__ . '/api/Dashboard/".Str::lower($name).".php';");
//        }
//
//        $this->info('----------------------------------');
//        $this->info('----------------------------------');
//        $folderMobile=base_path('app/Repositories/Mobile');
//        $filesMobile=File::directories($folderMobile);
//
//        foreach ($filesMobile as $file) {
//                        $name=Str::after($file, 'Mobile\\');
//            $this->info("require __DIR__ . '/api/Mobile/".Str::lower($name).".php';");
//        }


//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*

        $uses=[];
        $repositories=[];
        $interfaces=[];
        $folderDashboard=base_path('app/Repositories/Dashboard');
        $filesDashboard=File::directories($folderDashboard);
         foreach ($filesDashboard as $folder) {
             $folder_name=Str::after($folder, 'Dashboard\\');
             $use="use App\\Repositories\\Dashboard\\".$folder_name."\\";
             $files=File::files($folder);
             foreach ($files as $file) {
                 if (Str::contains($file,"Interface")) {
                     $interfaces[]=Str::after($file,$folder_name."\\");
                     $uses[]=$use.Str::after($file,$folder_name."\\");
                 }else{
                     $repositories[]=Str::after($file,$folder_name."\\");
                     $uses[]=$use.Str::after($file,$folder_name."\\");
                 }

             }
         }

        $folderMobile=base_path('app/Repositories/Mobile');
        $filesMobile=File::directories($folderMobile);
        foreach ($filesMobile as $folder) {
            $folder_name=Str::after($folder, 'Mobile\\');
            $use="use App\\Repositories\\Mobile\\".$folder_name."\\";
            $files=File::files($folder);
            foreach ($files as $file) {
                if (Str::contains($file,"Interface")) {
                    $interfaces[]=Str::after($file,$folder_name."\\");
                    $uses[]=$use.Str::after($file,$folder_name."\\");
                }else{
                    $repositories[]=Str::after($file,$folder_name."\\");
                    $uses[]=$use.Str::after($file,$folder_name."\\");
                }

            }
        }

         foreach ($uses as $us) {
             $this->info($us);
         }

        $this->info('----------------------------------');
        $this->info('----------------------------------');
        for ($i=0;$i<count($interfaces);$i++) {
            $cl=Str::before($interfaces[$i],".php")."::class,".Str::before($repositories[$i],".php")."::class";
            $this->info("\$this->app->bind(".$cl.");");
        }


//        $this->info('----------------------------------');
//        $this->info('----------------------------------');
//        $folderMobile=base_path('app/Repositories/Mobile');
//        $filesMobile=File::directories($folderMobile);
//
//        foreach ($filesMobile as $folder) {
//            $folder_name=Str::after($folder, 'Mobile\\');
//        }



    }
}
