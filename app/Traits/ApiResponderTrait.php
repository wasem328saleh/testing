<?php

namespace App\Traits;

use App\Http\Resources\ConfigAttributesResource;
use App\Models\Classification;
use App\Models\ConfigAttribute;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;

trait ApiResponderTrait
{

    public function relationship_exists($firstId,$secondId,$relationshipName,$model)
    {
        $exists = $model::whereHas($relationshipName, function ($query) use ($secondId) {
            $query->where('id', $secondId);
        })->where('id', $firstId)->exists();
        return $exists;
    }
    public function get_all_rules()
    {
//        $properties=[
//            'property_name' => ['required', 'string'],
//            'property_price' => ['required', 'numeric']
//        ];
//        $cars=[
//            'car_type' => ['required', 'string'],
//            'car_price' => ['required', 'numeric'],];
//        return array_merge($properties,$cars,$cars);

//        $attributes=[
//            [
//                'attribute_name'=>'property_name',
//                'rules'=>['required','string']
//            ],
//            [
//                'attribute_name'=>'property_price',
//                'rules'=>['required','numeric']
//            ],
//            [
//                'attribute_name'=>'car_type',
//                'rules'=>['required','string']
//            ],
//            [
//                'attribute_name'=>'car_price',
//                'rules'=>['required','numeric']
//            ]
//        ];
        $attributes=collect(ConfigAttributesResource::collection(ConfigAttribute::select(['attribute_name','rules'])->get()))->toArray();
//        return $attributes;
        $rules=[];
        foreach ($attributes as $attribute){
          $rules[$attribute['attribute_name']]=json_decode($attribute['rules']);
        }
        return $rules;


    }
    public function config_attributes($classification_id,$category_id=null)
    {

//        $attributes=[
//            [
//                'attribute_name'=>'property_name',
//                'rules'=>['required','string'],
//                'is_used'=>false,
//                'classification_id'=>1
//            ],
//            [
//                'attribute_name'=>'property_price',
//                'rules'=>['required','numeric'],
//                'is_used'=>true,
//                'classification_id'=>1
//            ],
//            [
//                'attribute_name'=>'car_type',
//                'rules'=>['required','string'],
//                'is_used'=>true,
//                'classification_id'=>2
//            ],
//            [
//                'attribute_name'=>'car_price',
//                'rules'=>['required','numeric'],
//                'is_used'=>true,
//                'classification_id'=>2
//            ]
//        ];
//
//        if ($category_id !=null)
//        {
//            return array_filter($attributes,function($attribute)use ($classification_id,$category_id){
//                return $attribute['classification_id']===$classification_id&&
//                    $attribute['category_id']===$category_id&&
//                    $attribute['is_required'];
//            });
//        }
//        return array_filter($attributes,function($attribute)use ($classification_id){
//            return $attribute['classification_id']===$classification_id&&$attribute['is_used'];
//        });

        if ($category_id !=null){
            $a1= collect(ConfigAttributesResource::collection(ConfigAttribute::where('category_id',$category_id)->where('classification_id',$classification_id)->where('is_required',true)->get()))->toArray();
            $a2= collect(ConfigAttributesResource::collection(ConfigAttribute::where('category_id',null)->where('classification_id',$classification_id)->where('is_required',true)->get()))->toArray();
            return array_merge($a1,$a2);
        }
        return collect(ConfigAttributesResource::collection(ConfigAttribute::where('classification_id',$classification_id)->where('is_required',true)->get()))->toArray();
    }
    public function switch_rules_classifications($classification_id)
    {
//        $a=$this->config_attributes($classification_id);
//        $attributes=[];
//        foreach($a as $attribute){
//            $attributes[$attribute['attribute_name']]=$attribute['rules'];
//        }
        $existingAttributes  = $this->config_attributes($classification_id);
        $allowedAttributes = array_map(function ($attribute) {
            return $attribute['attribute_name'];
        }, $existingAttributes);

        $property_rules=[
            'property_name'=>['required','string'],
            'property_price'=>['required','numeric'],
        ];
        $car_rules=[
            'car_type'=>['required','string'],
            'car_price'=>['required','numeric'],
        ];
        switch ($classification_id) {
            case 1:
            {
                return array_intersect_key($property_rules, array_flip($allowedAttributes));
                break;
            }
            case 2:
            {
//                return array_intersect_key($car_rules, array_flip($allowedAttributes));
                return $car_rules;
                break;
            }
            default:
                return [];
                break;
        }

//        return $rules;
//        return array_merge([
//            'classification_id'=>['required'],
//        ],$rules);
    }
    public function success($data = [], $message = null, $statusCode = Response::HTTP_OK): JsonResponse
    {
        if (!$message) {
            $message = Response::$statusTexts[$statusCode];
        }
        $info = [
            'message' => $message,
            'data' => $data,
        ];
//        if ($info['data'] == null) {
//            unset($info['data']);
//        }
        return response()->json($info, $statusCode);
    }

    public function error($message = null, $errors = null, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        if (!$message) {
            $message = Response::$statusTexts[$statusCode];
        }
        if (!is_array($errors)){
            $errors = [$errors];
        }
        return response()->json([
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }


    public function unauthorizedResponse($message = '', $errors = null): JsonResponse
    {
        return $this->error($message, $errors, Response::HTTP_UNAUTHORIZED);
    }

    public function forbiddenResponse($message = '', $errors = null): JsonResponse
    {
        return $this->error($message, $errors, Response::HTTP_FORBIDDEN);
    }

    public function badRequestResponse($message = null, $errors = null): JsonResponse
    {
        return $this->error($message, $errors, Response::HTTP_BAD_REQUEST);
    }

    public function notFoundResponse($message = null, $errors = null): JsonResponse
    {
        return $this->error($message, $errors, Response::HTTP_NOT_FOUND);
    }

    public function createdResponse($data = null, $message = ''): JsonResponse
    {
        return $this->success($data, $message, Response::HTTP_CREATED);
    }

    public function okResponse($data = null, $message = ''): JsonResponse
    {
        return $this->success($data, $message);
    }

    public function noContent($message = ''): JsonResponse
    {
        return $this->success(null, $message, Response::HTTP_OK);
    }
    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0011';

        else if ($input == "password")
            return 'E002';

        else if ($input == "mobile")
            return 'E003';

        else if ($input == "id_number")
            return 'E004';

        else if ($input == "birth_date")
            return 'E005';

        else if ($input == "agreement")
            return 'E006';

        else if ($input == "email")
            return 'E007';

        else if ($input == "city_id")
            return 'E008';

        else if ($input == "insurance_company_id")
            return 'E009';

        else if ($input == "activation_code")
            return 'E010';

        else if ($input == "longitude")
            return 'E011';

        else if ($input == "latitude")
            return 'E012';

        else if ($input == "id")
            return 'E013';

        else if ($input == "promocode")
            return 'E014';

        else if ($input == "doctor_id")
            return 'E015';

        else if ($input == "payment_method" || $input == "payment_method_id")
            return 'E016';

        else if ($input == "day_date")
            return 'E017';

        else if ($input == "specification_id")
            return 'E018';

        else if ($input == "importance")
            return 'E019';

        else if ($input == "type")
            return 'E020';

        else if ($input == "message")
            return 'E021';

        else if ($input == "reservation_no")
            return 'E022';

        else if ($input == "reason")
            return 'E023';

        else if ($input == "branch_no")
            return 'E024';

        else if ($input == "name_en")
            return 'E025';

        else if ($input == "name_ar")
            return 'E026';

        else if ($input == "gender")
            return 'E027';

        else if ($input == "nickname_en")
            return 'E028';

        else if ($input == "nickname_ar")
            return 'E029';

        else if ($input == "rate")
            return 'E030';

        else if ($input == "price")
            return 'E031';

        else if ($input == "information_en")
            return 'E032';

        else if ($input == "information_ar")
            return 'E033';

        else if ($input == "street")
            return 'E034';

        else if ($input == "branch_id")
            return 'E035';

        else if ($input == "insurance_companies")
            return 'E036';

        else if ($input == "photo")
            return 'E037';

        else if ($input == "logo")
            return 'E038';

        else if ($input == "working_days")
            return 'E039';

        else if ($input == "insurance_companies")
            return 'E040';

        else if ($input == "reservation_period")
            return 'E041';

        else if ($input == "nationality_id")
            return 'E042';

        else if ($input == "commercial_no")
            return 'E043';

        else if ($input == "nickname_id")
            return 'E044';

        else if ($input == "reservation_id")
            return 'E045';

        else if ($input == "attachments")
            return 'E046';

        else if ($input == "summary")
            return 'E047';

        else if ($input == "user_id")
            return 'E048';

        else if ($input == "mobile_id")
            return 'E049';

        else if ($input == "paid")
            return 'E050';

        else if ($input == "use_insurance")
            return 'E051';

        else if ($input == "doctor_rate")
            return 'E052';

        else if ($input == "provider_rate")
            return 'E053';

        else if ($input == "message_id")
            return 'E054';

        else if ($input == "hide")
            return 'E055';

        else if ($input == "checkoutId")
            return 'E056';

        else
            return "";
    }
    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

}
