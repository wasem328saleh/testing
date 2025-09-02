<?php

namespace App\Http\Requests\Mobile\Auth;

use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthUserRequest extends FormRequest
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

        if ($lastWord=="register")
        {
            if (Str::lower($this->user_type)===Str::lower('Merchant'))
            {
                return [
                    'first_name'=>['required','min:3'],
                    'last_name'=>['required','min:3'],
                    'email' => ['required','email',Rule::unique('users')->where(fn ($query) => $query->where('verify', true))],
//                    'email' => ['required','email','unique:users'],
                    'password' => ['required','min:8','confirmed'],
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
                    'email' => ['required','email',Rule::unique('users')->where(fn ($query) => $query->where('verify', true))],
//                    'email' => ['required','email','unique:users'],
                'password' => ['required','min:8','confirmed'],
                'region_id'=>['required','exists:regions,id'],
                'secondary_address'=>['required','min:4'],
                'phone_number'=>['required','min:10'],
                'image_profile'=>['image', 'mimes:jpeg,jpg,png'],
//                'device_uuid'=>['required','string','unique:security_settings'],
            ];
        }

        if ($lastWord=="verify")
        {
            return [
                'email' => ['required','email',Rule::exists('users')],
                'code'=>['required'],
                'device_token'=>['required']
            ];
        }
        if (Str::contains($path,"resend_verify")
            ||Str::contains($path,"resend_verify_reset_password")
            ||Str::contains($path,"forget_password"))
        {
            return [
                'email' => ['required','email',Rule::exists('users')]
            ];
        }

        if (Str::contains($path,"verify_reset_password_b"))
        {
            return [
                'email' => ['required','email',Rule::exists('users')],
                'code'=>['required']
            ];
        }
        if (Str::contains($path,"verify_reset_password"))
        {
            return [
                'email' => ['required','email',Rule::exists('users')],
                'code'=>['required'],
                'new_password'=>['required','min:8','confirmed']
            ];
        }
        if (Str::contains($path,'logout')){
            return [
                'device_token'=>['required']
            ];
        }
        if (Str::contains($path,'verify_new_email'))
        {
            return [
                'email'=>['required','email','unique:users,email'],
                'code'=>['required']
            ];
        }
        return [
            'email'=>['required','exists:users,email'],
            'password'=>['required'],
            'device_token'=>['required']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException($this->returnError($this->getErrorCode($errors[0]),$errors[0]));
    }
}
