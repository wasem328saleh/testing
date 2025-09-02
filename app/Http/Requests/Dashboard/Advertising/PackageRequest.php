<?php

namespace App\Http\Requests\Dashboard\Advertising;

use App\Http\Requests\PropertyRequest;
use App\Models\Classification;
use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

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

//        if ($lastWord=="register")
//        {
//            if (Str::lower($this->user_type)===Str::lower('Merchant'))
//            {
//                return [
//                    'first_name'=>['required','min:3'],
//                    'last_name'=>['required','min:3'],
//                    'email' => ['required','email','unique:users'],
//                    'password' => ['required','min:8','confirmed'],
//                    'region_id'=>['required','exists:regions,id'],
//                    'secondary_address'=>['required','min:4'],
//                    'phone_number'=>['required','min:10'],
//                    'device_uuid'=>['required','string','unique:security_settings'],
//                    'identification_papers' => ['array', 'required', 'max:10'],
//                    'identification_papers.*' => ['image', 'mimes:jpeg,jpg,png'],
//                ];
//            }
//            return [
//                'first_name'=>['required','min:3'],
//                'last_name'=>['required','min:3'],
//                'email' => ['required','email','unique:users'],
//                'password' => ['required','min:8','confirmed'],
//                'region_id'=>['required','exists:regions,id'],
//                'secondary_address'=>['required','min:4'],
//                'phone_number'=>['required','min:10'],
//                'device_uuid'=>['required','string','unique:security_settings'],
//            ];
//        }
//
//        if ($lastWord=="verify")
//        {
//            return [
//                'email' => ['required','email',Rule::exists('users')],
//                'code'=>['required'],
//                'device_token'=>['required']
//            ];
//        }
//        if (Str::contains($path,"resend_verify")
//            ||Str::contains($path,"resend_verify_reset_password")
//            ||Str::contains($path,"forget_password"))
//        {
//            return [
//                'email' => ['required','email',Rule::exists('users')]
//            ];
//        }
//        if (Str::contains($path,"verify_reset_password"))
//        {
//            return [
//                'email' => ['required','email',Rule::exists('users')],
//                'code'=>['required'],
//                'new_password'=>['required','min:8','confirmed']
//            ];
//        }
//        if (Str::contains($path,"verify_reset_password_b"))
//        {
//            return [
//                'email' => ['required','email',Rule::exists('users')],
//                'code'=>['required']
//            ];
//        }
        if (Str::contains($path,'package/all')){
            return [
                'role_name'=>['string','exists:roles,title'],
            ];
        }
        if (Str::contains($path,'package/add')){
            return [
                'title'=>['required','string','max:255'],
                'price'=>['required','numeric'],
                'validity_period'=>['required','numeric','min:1'],
                'number_of_advertisements'=>['required','numeric','min:1'],
                'validity_period_per_advertisement'=>['required','numeric','min:1'],
                'description'=>['required','string','max:255'],
                'user_type'=>['required','string','in:user,merchant,both'],
            ];
        }
        if (Str::contains($path,'package/update')){
            return [
                'package_id'=>['required','exists:advertising_packages,id'],
                'title'=>['string','max:255'],
                'price'=>['numeric'],
                'validity_period'=>['numeric','min:1'],
                'number_of_advertisements'=>['numeric','min:1'],
                'validity_period_per_advertisement'=>['numeric','min:1'],
                'description'=>['string','max:255'],
                'user_type'=>['string','in:user,merchant,both'],
            ];
        }
        if (Str::contains($path,'package/delete')){
            return [
                'package_id'=>['required','exists:advertising_packages,id'],
            ];
        }

        if (Str::contains($path,'ad/all')){
            return [
                'classification_id'=>['required','exists:classifications,id'],
            ];
        }
        if (Str::contains($path,'ad/add')){
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
        if (Str::contains($path,'ad/update')){
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
        if (Str::contains($path,'ad/delete')){
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
        if (Str::contains($path,'ad/change-status')){
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
        if (Str::contains($path,'feature/add')){
            return [
                'classification_id'=>['required','exists:classifications,id'],
                'name'=>['required','string','unique:features,name'],
            ];
        }
        if (Str::contains($path,'feature/update')){
            return [
                'feature_id'=>['required','exists:features,id'],
                'classification_id'=>['exists:classifications,id'],
                'name'=>['string','unique:features,name'],
            ];
        }
        if (Str::contains($path,'feature/delete')){
            return [
                'feature_id'=>['required','exists:features,id'],
            ];
        }


        if (Str::contains($path,'classification/add')){
            return [
                'name'=>['required','string','unique:classifications,name'],
            ];
        }
        if (Str::contains($path,'classification/update')){
            return [
                'classification_id'=>['required','exists:classifications,id'],
                'name'=>['string','unique:classifications,name'],
            ];
        }
        if (Str::contains($path,'classification/delete')){
            return [
                'classification_id'=>['required','exists:classifications,id'],
            ];
        }
        if (Str::contains($path,'classification/change-status')){
            return [
                'classification_id'=>['required','exists:classifications,id'],
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
        if (Str::contains($path,'get-my-advertising')
            ||Str::contains($path,'get-all-advertising')){
            return [
                'classification_id'=>['required','exists:classifications,id'],
            ];
        }
        if (Str::contains($path,'edited-my-advertising')){
            $classificationId = $this->input('classification_id');
            $classification_name=Classification::findOrFail($classificationId)->name;
            switch ($classification_name) {
                case 'properties':{
            return array_merge((new PropertyRequest())->get_rules('edit',$this),[
                'classification_id'=>['required','exists:classifications,id'],
            ]);
                }
                default:{
                    return [];
                }
            }
        }
        if (Str::contains($path,'get-features'))
        {
            return [
                'classification_id'=>['required','exists:classifications,id'],
                'category_id'=>['required','exists:property_sub_categories,id']
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
