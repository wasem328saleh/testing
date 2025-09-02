<?php

namespace App\Http\Requests\Dashboard\Address;

use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AddressRequest extends FormRequest
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
        /*
         * country_name
         *
         * country_id
         * country_name
         *
         * country_id
         *
         * city_name
         * country_id
         *
         * city_id
         * city_name
         * country_id
         *
         * city_id
         *
         * region_name
         * city_id
         *
         * region_id
         * region_name
         * city_id
         *
         * region_id
         */

//************************** Country *****************************
        if (Str::contains($path,'country/add'))
        {
            return [
                'country_name'=>['required','unique:countries,name'],
            ];
        }

        if (Str::contains($path,'country/update'))
        {
            return [
                'country_id'=>['required','exists:countries,id'],
                'country_name'=>['unique:countries,name'],
            ];
        }

        if (Str::contains($path,'country/delete'))
        {
            return [
                'country_id'=>['required','exists:countries,id']
            ];
        }


//************************** City *****************************

        if (Str::contains($path,'city/add'))
        {
            return [
                'city_name'=>['required','unique:cities,name'],
                'country_id'=>['required','exists:countries,id'],
            ];
        }

        if (Str::contains($path,'city/update'))
        {
            return [
                'city_id'=>['required','exists:cities,id'],
                'city_name'=>['unique:cities,name'],
                'country_id'=>['exists:countries,id'],
            ];
        }

        if (Str::contains($path,'city/delete'))
        {
            return [
                'city_id'=>['required','exists:cities,id'],
            ];
        }


        //************************** Region *****************************

        if (Str::contains($path,'region/add'))
        {
            return [
                'region_name'=>['required','unique:regions,name'],
                'city_id'=>['required','exists:cities,id'],
            ];
        }

        if (Str::contains($path,'region/update'))
        {
            return [
                'region_id'=>['required','exists:regions,id'],
                'region_name'=>['unique:regions,name'],
                'city_id'=>['exists:cities,id'],
            ];
        }

        if (Str::contains($path,'region/delete'))
        {
            return [
                'region_id'=>['required','exists:regions,id'],
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
