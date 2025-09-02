<?php

namespace App\Jobs;

use App\Services\ManageLangFiles;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class DeleteAllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $languages=array_keys(config('environment_system.available_languages'));
        foreach ($languages as $language) {
            $file1=base_path('lang\\'.$language.'\\'.'name.php');
            $file2=base_path('lang\\'.$language.'\\'.'title.php');
            $file3=base_path('lang\\'.$language.'\\'.'key.php');
            $content = "<?php\n\nreturn [\n];";
            File::put($file1, $content);
            File::put($file2, $content);
            File::put($file3, $content);
//            $name=new ManageLangFiles($language,"name");
//            $title=new ManageLangFiles($language,"title");
//            $key=new ManageLangFiles($language,"key");
//            $name->destroy_all();
//            $title->destroy_all();
//            $key->destroy_all();
        }
    }
}
