<?php

namespace App\Http\Requests\Dashboard\Properties;

use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class PropertiesRequest extends FormRequest
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

        if (Str::contains($path,'room-type/add'))
        {
            return [
                'name'=>['required','string','max:255','unique:room_types,name'],
            ];
        }
        if (Str::contains($path,'room-type/update'))
        {
            return [
                'room_type_id'=>['required','exists:room_types,id'],
                'name'=>['string','max:255','unique:room_types,name'],
            ];
        }
        if (Str::contains($path,'room-type/delete'))
        {
            return [
                'room_type_id'=>['required','exists:room_types,id'],
            ];
        }


        if (Str::contains($path,'main-categories/add'))
        {
            return [
                'name'=>['required','string','max:255','unique:property_main_categories,name'],
            ];
        }
        if (Str::contains($path,'main-categories/update'))
        {
            return [
                'main_category_id'=>['required','exists:property_main_categories,id'],
                'name'=>['string','max:255','unique:property_main_categories,name'],
            ];
        }
        if (Str::contains($path,'main-categories/delete'))
        {
            return [
                'main_category_id'=>['required','exists:property_main_categories,id'],
            ];
        }


        if (Str::contains($path,'sub-categories/add'))
        {
            return [
                'main_category_id'=>['required','exists:property_main_categories,id'],
                'name'=>['required','string','max:255','unique:property_sub_categories,name'],
            ];
        }
        if (Str::contains($path,'sub-categories/update'))
        {
            return [
                'sub_category_id'=>['required','exists:property_sub_categories,id'],
                'main_category_id'=>['exists:property_main_categories,id'],
                'name'=>['string','max:255','unique:property_sub_categories,name'],
            ];
        }
        if (Str::contains($path,'sub-categories/delete'))
        {
            return [
                'sub_category_id'=>['required','exists:property_sub_categories,id'],
            ];
        }


        if (Str::contains($path,'directions/add'))
        {
            return [
                'title'=>['required','string','max:255','unique:directions,title'],
            ];
        }
        if (Str::contains($path,'directions/update'))
        {
            return [
                'direction_id'=>['required','exists:directions,id'],
                'title'=>['string','max:255','unique:directions,title'],
            ];
        }
        if (Str::contains($path,'directions/delete'))
        {
            return [
                'direction_id'=>['required','exists:directions,id'],
            ];
        }

                if (Str::contains($path,'ownership_types/add'))
        {
            return [
                'name'=>['required','string','max:255','unique:ownership_types,name'],
            ];
        }
        if (Str::contains($path,'ownership_types/update'))
        {
            return [
                'ownership_type_id'=>['required','exists:ownership_types,id'],
                'name'=>['string','max:255','unique:ownership_types,name'],
            ];
        }
        if (Str::contains($path,'ownership_types/delete'))
        {
            return [
                'ownership_type_id'=>['required','exists:ownership_types,id'],
            ];
        }

        if (Str::contains($path,'pledge_types/add'))
        {
            return [
                'name'=>['required','string','max:255','unique:pledge_types,name'],
            ];
        }
        if (Str::contains($path,'pledge_types/update'))
        {
            return [
                'pledge_type_id'=>['required','exists:pledge_types,id'],
                'name'=>['string','max:255','unique:pledge_types,name'],
            ];
        }
        if (Str::contains($path,'pledge_types/delete'))
        {
            return [
                'pledge_type_id'=>['required','exists:pledge_types,id'],
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
