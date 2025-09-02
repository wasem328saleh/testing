<?php

namespace App\Http\Requests\Mobile\Config;

use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ConfigRequest extends FormRequest
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

        if (Str::contains($path,'get-config-attributes'))
        {
            return [
                'classification_id'=>['required','exists:classifications,id'],
                'category_id'=>['exists:property_sub_categories,id']
            ];
        }
        if (Str::contains($path,'get-features'))
        {
            return [
                'classification_id'=>['required','exists:classifications,id'],
                'category_id'=>['required','exists:property_sub_categories,id']
            ];
        }
        if (Str::contains($path,'change-language'))
        {
            $folder_path='lang';
            $directories_name=File::directories(base_path($folder_path));
            $languages=collect($directories_name)->map(function ($directory) use ($folder_path) {
                return Str::after($directory, $folder_path.'\\');
            })->toArray();
            return [
                'language'=>['required',Rule::in($languages)],
            ];
        }
        return [];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        throw new HttpResponseException($this->returnError($this->getErrorCode($errors[0]),$errors[0]));
    }
}
