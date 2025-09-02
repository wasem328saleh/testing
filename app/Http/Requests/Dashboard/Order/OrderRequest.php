<?php

namespace App\Http\Requests\Dashboard\Order;

use App\Http\Requests\PropertyRequest;
use App\Models\Classification;
use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class OrderRequest extends FormRequest
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

        if (Str::contains($path,'by-classification')){
            return [
                'classification_id'=>['required','exists:classifications,id'],
            ];
        }
        if (Str::contains($path,'accept')){
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
        if (Str::contains($path,'cancel')){
            $classificationId = $this->input('classification_id');
            $classification_name=Classification::findOrFail($classificationId)->name;
            switch ($classification_name) {
                case 'properties':{
            return array_merge((new PropertyRequest())->get_rules('property_id',$this),[
                'classification_id'=>['required','exists:classifications,id'],
                'reply'=>['required','string'],
            ]);
                }
                default:{
                    return [];
                }
            }
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
