<?php

namespace App\Http\Requests\Mobile\Services;

use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServicesRequest extends FormRequest
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

        if (Str::contains($path,'providers-by'))
        {
            return [
                'category_id'=>['required','exists:category_services,id'],
            ];
        }
        if (Str::contains($path,'send-order'))
        {
            return [
                'first_name'=>['required','string'],
                'last_name'=>['required','string'],
                'region_id'=>['required','exists:regions,id'],
                'phone_number'=>['required','string'],
                'secondary_address'=>['required','string'],
                'email'=>['email','unique:service_providers,email'],
                'description'=>['string'],
                'image'=>['image','mimes:jpeg,jpg,png'],
                'services'=>['required','array'],
                'services.*'=>['required','exists:category_services,id'],
            ];
        }
        if (Str::contains($path,'activation-edite'))
        {
            return [
                'provider_id'=>['required','exists:service_providers,id'],
            ];
        }
        if (Str::contains($path,'edite'))
        {
            return [
                'provider_id'=>['required','exists:service_providers,id'],
                'first_name'=>['string'],
                'last_name'=>['string'],
                'phone_number'=>['string'],
                'region_id'=>['exists:regions,id'],
                'secondary_address'=>['string'],
                'email'=>['email','unique:service_providers,email'],
                'description'=>['string'],
                'image'=>['image','mimes:jpeg,jpg,png'],
                'services'=>['array'],
                'services.*'=>['exists:category_services,id'],
            ];
        }
        if (Str::contains($path,'delete'))
        {
            return [
                'provider_id'=>['required','exists:service_providers,id'],
            ];
        }
        if (Str::contains($path,'rate-provider'))
        {
            return [
                'provider_id'=>['required','exists:service_providers,id'],
                'score'=>['required','numeric','min:1','max:5'],
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
