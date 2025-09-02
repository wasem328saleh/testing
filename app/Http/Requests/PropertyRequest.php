<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Country;
use App\Models\Property;
use App\Models\PropertyMainCategory;
use App\Rules\RelationshipExists;
use App\Traits\ApiResponderTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PropertyRequest extends FormRequest
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

        return [];
    }
    public function get_rules($path,$request)
    {
        $result_rules=[];
        $rules=$this->get_all_rules();

        if (Str::contains($path,'add')){
            $classificationId = $request->input('classification_id');
            $existingAttributes  = $this->config_attributes(intval($classificationId),intval($request->input('sub_category_id')??$request->input('sub_category_id')));
            $attributes_name=[];
            foreach ($existingAttributes as $attribute){
                $attributes_name[]=$attribute['attribute_name'];
            }
            $filteredRules = array_intersect_key($rules, array_flip($attributes_name));
            if (Str::lower(Auth::user()->roles()->first()->title)===Str::lower('User')){
                return array_merge([
                    // 'identification_papers' => ['array', 'required', 'max:10'],
                    // 'identification_papers.*' => ['image', 'mimes:jpeg,jpg,png'],
                    'identification_papers' => ['array','max:10'],
                    'identification_papers.*' => ['mimes:jpeg,jpg,png','max:2048'],
                    'publication_type'=>['required',Rule::in(['sale','rent'])],
                    'main_category_id'=>['required','exists:property_main_categories,id'],
                    'sub_category_id'=>['required','exists:property_sub_categories,id',new RelationshipExists($request->input('main_category_id'),$request->input('sub_category_id'),'sub_categories',PropertyMainCategory::class)],
                    'area'=>['required','numeric'],
                    'ownership_type_id'=>['required','exists:ownership_types,id'],
                    'price'=>['required','numeric'],
                    'country_id' => ['required', 'exists:countries,id'],
                    'city_id' => ['required', 'exists:cities,id',new RelationshipExists($request->input('country_id'),$request->input('city_id'),'cities',Country::class)],
                    'region_id' => ['required', 'exists:regions,id', new RelationshipExists($request->input('city_id'),$request->input('region_id'),'regions',City::class)],
                    'secondary_address'=>['required','string'],
                    'features'=>['required','array'],
                    'features.*'=>['required','numeric','exists:features,id'],
                    'medias'=>['required','array'],
                    'medias.*'=>['required','file','mimes:jpeg,jpg,png,gif','max:2048'],
                    'ownership_papers'=>['required','array'],
                    'ownership_papers.*'=>['required','file','mimes:jpeg,jpg,png,gif','max:2048'],
                    'directions'=>['required','array'],
                    'directions.*'=>['required','numeric','exists:directions,id'],
                    'rent_type'=>['required_if:publication_type,rent','prohibited_if:publication_type,sale','string','in:yearly,monthly,daily'],
                    'description'=>['string'],
                ],$filteredRules);
            }
            $result_rules= array_merge([
                'publication_type'=>['required',Rule::in(['sale','rent'])],
                'main_category_id'=>['required','exists:property_main_categories,id'],
                'sub_category_id'=>['required','exists:property_sub_categories,id',new RelationshipExists($request->input('main_category_id'),$request->input('sub_category_id'),'sub_categories',PropertyMainCategory::class)],
                'area'=>['required','numeric'],
                'ownership_type_id'=>['required','exists:ownership_types,id'],
                'price'=>['required','numeric'],
                'country_id' => ['required', 'exists:countries,id'],
                'city_id' => ['required', 'exists:cities,id',new RelationshipExists($request->input('country_id'),$request->input('city_id'),'cities',Country::class)],
                'region_id' => ['required', 'exists:regions,id', new RelationshipExists($request->input('city_id'),$request->input('region_id'),'regions',City::class)],
                'secondary_address'=>['required','string'],
                'features'=>['required','array'],
                'features.*'=>['required','numeric','exists:features,id'],
                'medias'=>['required','array'],
                'medias.*'=>['required','file','mimes:jpeg,jpg,png,gif','max:2048'],
                // 'ownership_papers'=>['required','array'],
                // 'ownership_papers.*'=>['required','image','mimes:jpeg,jpg,png,gif'],
                'directions'=>['required','array'],
                'directions.*'=>['required','numeric','exists:directions,id'],
                'rent_type'=>['required_if:publication_type,rent','prohibited_if:publication_type,sale','string','in:yearly,monthly,daily'],
                'description'=>['string'],
            ],$filteredRules);
        }
        if (Str::contains($path,'property_id')){
            $result_rules= [
                'property_id'=>['required','exists:properties,id'],
            ];
        }
        if (Str::contains($path,'add-medias')){
            $result_rules= [
                'property_id'=>['required','exists:properties,id'],
                'medias'=>['required','array'],
                'medias.*'=>['required','image','mimes:jpeg,jpg,png,gif'],
            ];
        }
        if (Str::contains($path,'delete-media')){
            $result_rules= [
                'property_id'=>['required','exists:properties,id'],
                'media_id'=>['required','exists:medias,id',
                    new RelationshipExists(
                        $request->input('property_id'),
                        $request->input('media_id'),
                        'medias',
                        Property::class)
                ],
            ];
        }
        return $result_rules;
    }
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        throw new HttpResponseException($this->returnError($this->getErrorCode($errors[0]),$errors[0]));
    }
}
