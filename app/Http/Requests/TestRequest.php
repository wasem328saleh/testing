<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Country;
use App\Models\PropertyMainCategory;
use App\Rules\RelationshipExists;
use App\Rules\RentTypeRule;
use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TestRequest extends FormRequest
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

        $rules=$this->get_all_rules();

        if (Str::contains($path,'test-get-rules-request')){
            return array_merge((new PropertyRequest())->get_rules('test-get-rules-request',$this),[
                'classification_id'=>['required','exists:classifications,id'],
            ]);
        }
//        if (Str::contains($path,'test-request')){
//            $classificationId = $this->input('classification_id');
////            if ($this->input('category_id')!=null)
////            {
////                $existingAttributes  = $this->config_attributes(intval($classificationId),intval($this->input('category_id')));
////            }
//            $existingAttributes  = $this->config_attributes(intval($classificationId),intval($this->input('sub_category_id')??$this->input('sub_category_id')));
//            $attributes_name=[];
//            foreach ($existingAttributes as $attribute){
//                $attributes_name[]=$attribute['attribute_name'];
//            }
//            $filteredRules = array_intersect_key($rules, array_flip($attributes_name));
//
//            return array_merge([
//                'classification_id'=>['required','exists:classifications,id'],
//                'publication_type'=>['required',Rule::in(['sale','rent'])],
//                'main_category_id'=>['required','exists:property_main_categories,id'],
//                'sub_category_id'=>['required','exists:property_sub_categories,id',new RelationshipExists($this->main_category_id,$this->sub_category_id,'sub_categories',PropertyMainCategory::class)],
//                'area'=>['required','numeric'],
//                'ownership_type_id'=>['required','exists:ownership_types,id'],
//                'price'=>['required','numeric'],
//                'country_id' => ['required', 'exists:countries,id'],
//                'city_id' => ['required', 'exists:cities,id',new RelationshipExists($this->country_id,$this->city_id,'cities',Country::class)],
//                'region_id' => ['required', 'exists:regions,id', new RelationshipExists($this->city_id,$this->region_id,'regions',City::class)],
//                'secondary_address'=>['required','string'],
//                'features'=>['required','array'],
//                'features.*'=>['required','numeric','exists:features,id'],
//                'medias'=>['required','array'],
//                'medias.*'=>['required','image','mimes:jpeg,jpg,png,gif'],
//                'ownership_papers'=>['required','array'],
//                'ownership_papers.*'=>['required','image','mimes:jpeg,jpg,png,gif'],
//                'directions'=>['required','array'],
//                'directions.*'=>['required','numeric','exists:directions,id'],
//                'rent_type'=>['required_if:publication_type,rent','prohibited_if:publication_type,sale','string','in:yearly,monthly,daily'],
//                'description'=>['string'],
//            ],$filteredRules);
//        }
//        if (Str::contains($path,'test-rule')){
////            return [
////                'publication_type'=>['required',Rule::in(['sale','rent'])],
////                'rent_type'=>['required_if:publication_type,rent',
////                    'prohibited_if:publication_type,sale',
////                    'string','in:yearly,monthly,daily'],
////            ];
////            return [
////                'country_id' => ['required', 'exists:countries,id'],
////                'city_id' => ['required', 'exists:cities,id',new RelationshipExists($this->country_id,$this->city_id,'cities',Country::class)],
////                'region_id' => ['required', 'exists:regions,id', new RelationshipExists($this->city_id,$this->region_id,'regions',City::class)],
////            ];
//
//            return [
//                'main_category_id'=>['required','exists:property_main_categories,id'],
//                'sub_category_id'=>['required','exists:property_sub_categories,id',new RelationshipExists($this->main_category_id,$this->sub_category_id,'sub_categories',PropertyMainCategory::class)],
//            ];
//        }
        return [];
    }
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        throw new HttpResponseException($this->returnError($this->getErrorCode($errors[0]),$errors[0]));
    }
}
