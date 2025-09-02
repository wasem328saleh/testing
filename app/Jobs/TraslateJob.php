<?php

namespace App\Jobs;

use App\Services\ManageLangFiles;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TraslateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $string;
    protected $fileName;
    protected $methodeName;
    protected $object;
    /**
     * Create a new job instance.
     */
    public function __construct($object,$string,$fileName,$methodeName)
    {
        $this->string = $string;
        $this->fileName = $fileName;
        $this->methodeName = $methodeName;
        $this->object = $object;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $localization=new GoogleTranslate();
        $localization->translate(strval($this->string));
        $lang=$localization->getLastDetectedSource();
        $languages=array_keys(config('environment_system.available_languages'));

        $methode=$this->methodeName;
        $tr_key=new GoogleTranslate();
        $tr_key->setSource($lang);
        $tr_key->setTarget('en');
        $key=str_replace(' ','_',Str::lower($tr_key->translate(strval($this->string))));
        switch ($methode) {
            case "store":{
                $this->object->translation()->create([
                    'key'=>$key,
                    'file'=>$this->fileName
                ]);
                break;
            }
            case "update":{
                $this->object->translation()->update([
                    'key'=>$key,
                ]);
                break;
            }
            case "destroy":{
                $this->object->translation()->delete();
                break;
            }
            default:return;
        }


        foreach ($languages as $language) {
            $tr=new GoogleTranslate();
            $tr->setSource($lang);
            $tr->setTarget($language);
            $value=$tr->translate(strval($this->string));
            $ml=new ManageLangFiles($language,$this->fileName);
            $ml->$methode($key,$value);
        }

    }
}
