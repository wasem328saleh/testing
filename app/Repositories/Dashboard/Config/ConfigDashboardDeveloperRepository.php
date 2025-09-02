<?php

namespace App\Repositories\Dashboard\Config;

use App\Http\Resources\ConfigAttributesResource;
use App\Http\Resources\IdNameResource;
use App\Models\Classification;
use App\Models\ConfigAttribute;
use App\Models\Feature;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ConfigDashboardDeveloperRepository implements ConfigDashboardDeveloperRepositoryInterface
{
    use GeneralTrait;
    public function get_classifications()
    {
        // TODO: Implement get_classifications() method.
        try {
            $classifications=Classification::where('active',1)->with('translation')->get();
            return $this->returnData('classifications',IdNameResource::collection($classifications));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_config_attributes($request)
    {
        // TODO: Implement get_config_attributes() method.
        try {
            $config_attributes=ConfigAttribute::select([
                'id',
                'attribute_name',
                'classification_id',
                'category_id'
            ])
                ->where('classification_id',request()->classification_id)
                ->where('category_id',request()->category_id)
                ->where('is_required',true)
                ->whereNot('attribute_name','Like','%.*.%')
                ->get();
            if (request()->has('use_filter')) {
                $config_attributes=ConfigAttribute::select([
                    'id',
                    'attribute_name',
                    'classification_id',
                    'category_id'
                ])
                    ->where('classification_id',request()->classification_id)
                    ->where('category_id',request()->category_id)
                    ->where('is_used_for_filtering',true)
                    ->get();
            }
            return $this->returnData('config_attributes',ConfigAttributesResource::collection($config_attributes));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_features($request)
    {
        // TODO: Implement get_features() method.
        try {
            $features=Feature::where('classification_id',request()->classification_id)->with('translation')->get();
            return $this->returnData('features',IdNameResource::collection($features));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_languages()
    {
        // TODO: Implement get_languages() method.
        try {
            $folder_path='lang';
            $directories_name=File::directories(base_path($folder_path));
            $languages=collect($directories_name)->map(function ($directory) use ($folder_path) {
                return Str::after($directory, $folder_path.'\\');
            });
            return $this->returnData('languages',$languages);
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function get_language_app()
    {
        // TODO: Implement get_language_app() method.
        try {
            $lang=$this->getLanguageApplication();
            return $this->returnData('language',$lang,trans('messages.language_application'));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function change_language($request)
    {
        // TODO: Implement change_language() method.
        try {
            $lang=request()->language;
            $this->ChangeLanguage($lang);
            if ($lang=='ar'){
                return $this->returnSuccessMessage('تم تغيير اللغة بنجاح');
            }
            return $this->returnSuccessMessage('The language has been changed successfully');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_all_config()
    {
        // TODO: Implement get_all_config() method.
        try {
            $config_attributes=ConfigAttribute::select([
                'id',
                'attribute_name',
                'is_required',
                'is_used_for_filtering',
                'classification_id',
                'category_id'
            ])
            ->whereNot('attribute_name','Like','%.*.%')
                ->get();
            return $this->returnData('config_attributes',ConfigAttributesResource::collection($config_attributes));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
