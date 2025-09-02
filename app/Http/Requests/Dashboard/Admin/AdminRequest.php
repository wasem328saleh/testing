<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class AdminRequest extends FormRequest
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

        if (Str::contains($path,'add-user'))
        {
            if (Str::lower($this->user_type)===Str::lower('Merchant'))
            {
                return [
                    'first_name'=>['required','min:3'],
                    'last_name'=>['required','min:3'],
                    'email' => ['required','email','unique:users'],
                    'region_id'=>['required','exists:regions,id'],
                    'secondary_address'=>['required','min:4'],
                    'phone_number'=>['required','min:10'],
                    'image_profile'=>['image', 'mimes:jpeg,jpg,png'],
//                    'device_uuid'=>['required','string','unique:security_settings'],
                    'identification_papers' => ['array', 'required', 'max:10'],
                    'identification_papers.*' => ['image', 'mimes:jpeg,jpg,png'],
                ];
            }
            return [
                'first_name'=>['required','min:3'],
                'last_name'=>['required','min:3'],
                'email' => ['required','email','unique:users'],
                'region_id'=>['required','exists:regions,id'],
                'secondary_address'=>['required','min:4'],
                'phone_number'=>['required','min:10'],
                'image_profile'=>['image', 'mimes:jpeg,jpg,png'],
//                'device_uuid'=>['required','string','unique:security_settings'],
            ];
        }

        if (Str::contains($path,'edite-user')){
            return [
                'user_id'=>['required','exists:users,id'],
                'first_name'=>['min:3'],
                'last_name'=>['min:3'],
                'region_id'=>['exists:regions,id'],
                'secondary_address'=>['min:4'],
                'phone_number'=>['min:10'],
                'image'=>['image', 'mimes:jpeg,jpg,png'],
                'email'=>['email','unique:users,email'],
            ];
        }
        if (Str::contains($path,'delete-user')){
            return [
                'user_id'=>['required','exists:users,id'],
            ];
        }

        if (Str::contains($path,'info-contact/add')){
            return [
                'key'=>['required','string','min:3','unique:system_contact_information,key'],
                'value'=>['required','string','min:3'],
            ];
        }

        if (Str::contains($path,'info-contact/edit')){
            return [
                'info_id'=>['required','exists:system_contact_information,id'],
                'key'=>['string','min:3','unique:system_contact_information,key'],
                'value'=>['string','min:3'],
            ];
        }

        if (Str::contains($path,'info-contact/delete')){
            return [
                'info_id'=>['required','exists:system_contact_information,id']
            ];
        }

        if (Str::contains($path,'get-config-attributes'))
        {
            return [
                'classification_id'=>['required','exists:classifications,id'],
                'category_id'=>['exists:property_sub_categories,id']
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
