<?php

namespace App\Http\Requests\Mobile\Advertising;

use App\Http\Requests\PropertyRequest;
use App\Models\Classification;
use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
{
    use ApiResponderTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $url = $this->fullUrl();
        $path = parse_url($url, PHP_URL_PATH);
        $lastWord = basename($path);

        if (Str::contains($path,'all-packages')){
            return [
                'role_name'=>['string','exists:roles,title'],
            ];
        }
        if (Str::contains($path,'subscribe-in-package')){
            return [
                'package_id'=>['required','exists:advertising_packages,id'],
            ];
        }
        if (Str::contains($path,'add-advertising')){
            $classificationId = $this->input('classification_id');
            $classification_name=Classification::findOrFail($classificationId)->name;
            switch ($classification_name) {
                case 'properties':{
            return array_merge((new PropertyRequest())->get_rules('add',$this),[
                'classification_id'=>['required','exists:classifications,id'],
            ]);
                }
                default:{
                    return [];
                }
            }
        }
        if (Str::contains($path,'get-all-advertising')
            ||Str::contains($path,'get-all-advertising-sale')
            ||Str::contains($path,'get-all-advertising-rent')){
            return [
                'classification_id'=>['required','exists:classifications,id'],
                'city_id'=>['exists:cities,id'],
            ];
        }
        if (Str::contains($path,'get-my-advertising')
            ||Str::contains($path,'filter-advertising')){
            return [
                'classification_id'=>['required','exists:classifications,id'],
            ];
        }
        if (Str::contains($path,'edited-my-advertising')
            ||Str::contains($path,'deleted-my-advertising')
            ||Str::contains($path,'edited-activation-advertising')){
            $classificationId = $this->input('classification_id');
            $classification_name=Classification::findOrFail($classificationId)->name;
            switch ($classification_name) {
                case 'properties':{
            return array_merge((new PropertyRequest())->get_rules('property_id',$this),[
                'classification_id'=>['required','exists:classifications,id'],
            ]);
                }
                default:{
                    return [];
                }
            }
        }
        if (Str::contains($path,'advertising-by-city')){
            return [
                'classification_id'=>['required','exists:classifications,id'],
                'city_id'=>['required','exists:cities,id'],
            ];
        }
        if (Str::contains($path,'resubscribe')){
            return [
                'subscribe_id'=>['required','exists:subscribes,id'],
            ];
        }
        if (Str::contains($path,'rate-advertising')){
            return [
                'advertisement_id'=>['required','exists:advertisements,id'],
                'score'=>['required','numeric','min:1','max:5'],
            ];
        }
        if (Str::contains($path,'add-medias')){
            $classificationId = $this->input('classification_id');
            $classification_name=Classification::findOrFail($classificationId)->name;
            switch ($classification_name) {
                case 'properties':{
                    return array_merge((new PropertyRequest())->get_rules('add-medias',$this),[
                        'classification_id'=>['required','exists:classifications,id'],
                    ]);
                }
                default:{
                    return [];
                }
            }
        }
        if (Str::contains($path,'delete-media')){
            $classificationId = $this->input('classification_id');
            $classification_name=Classification::findOrFail($classificationId)->name;
            switch ($classification_name) {
                case 'properties':{
                    return array_merge((new PropertyRequest())->get_rules('delete-media',$this),[
                        'classification_id'=>['required','exists:classifications,id'],
                    ]);
                }
                default:{
                    return [];
                }
            }
        }
        if (Str::contains($path,'search-serialNumber')){
            return [
                'serial_number'=>['string','nullable'],
            ];
        }
        return [

        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        throw new HttpResponseException($this->returnError($this->getErrorCode($errors[0]),$errors[0]));
    }
}
