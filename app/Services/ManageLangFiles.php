<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use function PHPUnit\Framework\isEmpty;

class ManageLangFiles
{
    protected $filePath;

    public function __construct($lang,$fileName)
    {
        $this->filePath = base_path('lang\\'.$lang.'\\'.$fileName.'.php'); // تغيير 'ar' للغة المطلوبة
    }

    public function get()
    {
        $translations = include $this->filePath;
        return $translations;
    }
    public function store($key,$value)
    {

        $translations = include $this->filePath;
        $translations[$key] = $value;

        $this->saveToFile($translations);
    }

    public function update($key,$value)
    {

        $translations = include $this->filePath;
        $translations[$key] = $value;
        $this->saveToFile($translations);
    }


    public function destroy($key,$value=null)
    {
        $translations = include $this->filePath;
        unset($translations[$key]);

        $this->saveToFile($translations);
    }

    public function destroy_all($key=null,$value=null)
    {
        $translations = include $this->filePath;
        $translations=[];
        $this->saveToFile($translations);
    }

    protected function saveToFile($data)
    {
        if(count($data)==0){
            $content = "<?php\n\nreturn [\nwasem saleh\n];";
            File::put($this->filePath, $content);
        }else{
            $content = "<?php\n\nreturn [\n";
            foreach ($data as $key => $value) {
                $content .= "    '{$key}' => \"{$value}\",\n";
            }
            $content .= "];";

            File::put($this->filePath, $content);
        }

    }
}
