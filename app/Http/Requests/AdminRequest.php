<?php

namespace App\Http\Requests;

use App\Traits\ApiResponderTrait;
use Dotenv\Util\Str;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

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

        if (\Illuminate\Support\Str::contains($path,"add-employee")){
            return [
                'full_name'=>['required','string'],
                'phone_number'=>['required','numeric'],
                'address'=>['required','string']
            ];
        }

//        if (\Illuminate\Support\Str::contains($path,"delete-employee")){
//
//            return [
//                'employee_id'=>['required',Rule::exists('employees','id')]
//            ];
//        }
//        if (\Illuminate\Support\Str::contains($path,"edit-employee")){
//
//        }
//        if (\Illuminate\Support\Str::contains($path,"show-employee")){
//
//        }
//        if (\Illuminate\Support\Str::contains($path,"get_all-tasks-employee")){
//
//        }
        if (\Illuminate\Support\Str::contains($path,"active-not-active")){
            return [
                'employee_id'=>['required',Rule::exists('employees','id')],
                'active_value'=>['required','boolean']
            ];
        }
//        if (\Illuminate\Support\Str::contains($path,"payment")){
//
//        }
        if (\Illuminate\Support\Str::contains($path,"edit-cost-per-hour")){
return [
    'cost'=>['required','numeric']
];
        }
//        return [
//            'user_name'=>['required',!(Rule::exists('users','user_name'))],
//            'password'=>['required']
//        ];
        return [
            'employee_id'=>['required',Rule::exists('employees','id')]
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        throw new HttpResponseException($this->returnError($this->getErrorCode($errors[0]),$errors[0]));
    }
}
