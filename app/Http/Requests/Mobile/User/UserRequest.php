<?php

namespace App\Http\Requests\Mobile\User;

use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{    use ApiResponderTrait;
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

        if (Str::contains($path,'update-my-info')){
            return [
                'first_name'=>['min:3'],
                'last_name'=>['min:3'],
                'region_id'=>['exists:regions,id'],
                'secondary_address'=>['min:4'],
                'phone_number'=>['min:10'],
                'image'=>['image', 'mimes:jpeg,jpg,png'],
                'email'=>['email','unique:users,email'],
            ];
        }
        if (Str::contains($path,'add-favourite')){
            return [
                'advertisement_id'=>['exists:advertisements,id'],
            ];
        }
        if (Str::contains($path,'delete-favourite')){
            return [
                'favorite_id'=>['exists:favorites,id'],
            ];
        }
        if (Str::contains($path,'get-user-profile')){
            return [
                'user_id'=>['exists:users,id'],
            ];
        }
        if (Str::contains($path,'get-notification-device')){
            return [
                'device_token'=>['required']
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
