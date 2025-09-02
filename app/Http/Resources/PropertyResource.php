<?php

namespace App\Http\Resources;

use App\Models\Classification;
use App\Models\ConfigAttribute;
use App\Models\PropertyMainCategory;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes_name=collect(ConfigAttributesResource::collection(
            ConfigAttribute::select('attribute_name')
                ->where('classification_id',Classification::where('name','properties')->first()->id)
                ->whereNull('category_id')
                ->where('is_required',true)
                ->get()))
            ->pluck('attribute_name')
            ->toArray();
        $w=collect(ConfigAttributesResource::collection(
            ConfigAttribute::select('attribute_name')
                ->where('category_id',$this->sub_category->id)
                ->where('is_required',true)
                ->get()))
            ->pluck('attribute_name')
            ->toArray();
        $attributes=array_filter(array_merge($attributes_name,$w),function($v){
            return !Str::contains($v,'.*.');
        });
        $arade_id=PropertyMainCategory::where('name','أراضي')->first()->id;
        $user=Auth::user();

        if ($user){
            if ($this->publication_type=="sale"){
                if ($this->sub_category->main_category->id==$arade_id){
                    return [
                        'id'=>$this->id,
                        'serial_number'=>$this->serial_number,
                     'status'=>$this->status,
                        'advertisement_id'=>$this->whenLoaded('advertisement',function (){
                            return $this->advertisement->id;
                        }),
                        'date'=>Carbon::parse($this->advertisement->created_at)->format('Y-m-d H:i:s'),
                        'user_information'=>new UserResource($this->order->user->load(['region','roles'])),
                        'is_favorite'=>$user->favorite()->whereHasMorph(
                            'favoriteable',
                            ['App\Models\Property'], // تحدید المودیلات المسموحة
                            function ($query) {
                                $query->where('id', $this->id);
                            }
                        )->exists(),
                        'area'=>$this->area,
                        'price'=>$this->price_history_price,
                        'price_history'=>$this->price_history,
                        'publication_type'=>$this->publication_type,
                        'rent_type'=>" ",
                        'address'=>$this->whenLoaded('region',function(){
                            return collect(new AddressResource($this->region->load('translation')))->put('secondary_address',$this->secondary_address);
                        }),
                        'ownership_type'=>$this->whenLoaded('ownership_type',function(){
                            return new IdNameResource($this->ownership_type->load('translation'));
                        }),
                        'main_category'=>new IdNameResource($this->sub_category->main_category->load('translation')),
                        'sub_category'=>new IdNameResource($this->sub_category->load('translation')),
//                        'description_e'=>$this->description,
                        'description'=>$this->when(($this->description
                            &&in_array("description",$attributes)),function(){
                            return $this->description;
                        }),
                        'age'=>$this->when(($this->age
                            &&in_array("age",$attributes)),function(){
                            return $this->age;
                        }),
                        'map_points'=>$this->when(($this->map_points
                            &&in_array("map_points",$attributes)),function(){
                            return json_decode($this->map_points);
                        }),
                        'pledge_type'=>$this->when(($this->pledge_type_id
                            &&in_array("pledge_type_id",$attributes)),function(){
                            return $this->whenLoaded('pledge_type',function() {
                                return new IdNameResource($this->pledge_type->load('translation'));
                            });
                        }),
                        'rooms_number'=>'-',
                        'rooms'=>$this->whenLoaded('rooms',function() use($attributes){
                            return $this->when(($this->rooms
                                &&in_array("rooms",$attributes)),function(){
                                return PropertyRoomResource::collection($this->rooms->load('room_type'));
                            });
                        }),
                        'directions'=>$this->whenLoaded('directions',function(){
                            return $this->when($this->directions,function(){
                                return IdTitleResource::collection($this->directions->load('translation'));
                            });
                        }),
                        'ownership_papers'=>$this->whenLoaded('ownership_papers',function(){
                            return $this->when($this->ownership_papers,function(){
                                return IdUrlResource::collection($this->ownership_papers);
                            });
                        }),
                        'features'=>$this->whenLoaded('features',function(){
                            return $this->when($this->features,function(){
                                return PropertyFeatureResource::collection($this->features->load('feature.translation'));
                            });
                        }),
                        'rating'=>$this->whenLoaded('ratings',function(){
                            return $this->when($this->ratings,function(){
                                $ssc=count(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                                $ss=array_sum(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                                if ($ss==0)
                                {
                                    return $ssc;
                                }
                                return round($ss/$ssc,1);
                            });
                        }),
                        'detailed_attributes'=>$this->whenLoaded('detailed_attributes',function(){
                            return $this->when($this->detailed_attributes,function(){
                                return KeyValueResource::collection($this->detailed_attributes->load('translation'));
                            });
                        }),
                        'medias_url'=>$this->whenLoaded('medias',function(){
                            $urls=$this->medias->pluck('url')->toArray();
                            $result=[];
                            foreach ($urls as $url)
                            {
                                $result[]=url($url);
                            }
                            return $result;
                        }),
                        'medias'=>$this->whenLoaded('medias',function(){
                            return $this->when($this->medias,function(){
                                return MediaResource::collection($this->medias);
                            });
                        }),

                    ];
                }
                return [
                    'id'=>$this->id,
                    'serial_number'=>$this->serial_number,
              'status'=>$this->status,
                    'advertisement_id'=>$this->whenLoaded('advertisement',function (){
                        return $this->advertisement->id;
                    }),
                    'date'=>Carbon::parse($this->advertisement->created_at)->format('Y-m-d H:i:s'),
                    'user_information'=>new UserResource($this->order->user->load(['region','roles'])),
                    'is_favorite'=>$user->favorite()->whereHasMorph(
                        'favoriteable',
                        ['App\Models\Property'], // تحدید المودیلات المسموحة
                        function ($query) {
                            $query->where('id', $this->id);
                        }
                    )->exists(),
                    'area'=>$this->area,
                    'price'=>$this->price_history_price,
                    'price_history'=>$this->price_history,
                    'publication_type'=>$this->publication_type,
                    'rent_type'=>" ",
                    'address'=>$this->whenLoaded('region',function(){
                        return collect(new AddressResource($this->region->load('translation')))->put('secondary_address',$this->secondary_address);
                    }),
                    'ownership_type'=>$this->whenLoaded('ownership_type',function(){
                        return new IdNameResource($this->ownership_type->load('translation'));
                    }),
                    'main_category'=>new IdNameResource($this->sub_category->main_category->load('translation')),
                    'sub_category'=>new IdNameResource($this->sub_category->load('translation')),
//                'description'=>$this->description,
//                    'description_e'=>$this->description,
                    'description'=>$this->when(($this->description
                        &&in_array("description",$attributes)),function(){
                        return $this->description;
                    }),
                    'age'=>$this->when(($this->age
                        &&in_array("age",$attributes)),function(){
                        return $this->age;
                    }),
                    'map_points'=>$this->when(($this->map_points
                        &&in_array("map_points",$attributes)),function(){
                        return json_decode($this->map_points);
                    }),
                    'pledge_type'=>$this->when(($this->pledge_type_id
                        &&in_array("pledge_type_id",$attributes)),function(){
                        return $this->whenLoaded('pledge_type',function() {
                            return new IdNameResource($this->pledge_type->load('translation'));
                        });
                    }),
                    'rooms_number'=>$this->whenLoaded('rooms',function() use($attributes){
                        return $this->when(($this->rooms
                            &&in_array("rooms",$attributes)),function(){
                            return $this->counter_rooms_number($this->rooms);
                        });
                    }),
                    'rooms'=>$this->whenLoaded('rooms',function() use($attributes){
                        return $this->when(($this->rooms
                            &&in_array("rooms",$attributes)),function(){
                            return PropertyRoomResource::collection($this->rooms->load('room_type'));
                        });
                    }),
                    'directions'=>$this->whenLoaded('directions',function(){
                        return $this->when($this->directions,function(){
                            return IdTitleResource::collection($this->directions->load('translation'));
                        });
                    }),
                    'ownership_papers'=>$this->whenLoaded('ownership_papers',function(){
                        return $this->when($this->ownership_papers,function(){
                            return IdUrlResource::collection($this->ownership_papers);
                        });
                    }),
                    'features'=>$this->whenLoaded('features',function(){
                        return $this->when($this->features,function(){
                            return PropertyFeatureResource::collection($this->features->load('feature.translation'));
                        });
                    }),
                    'rating'=>$this->whenLoaded('ratings',function(){
                        return $this->when($this->ratings,function(){
                            $ssc=count(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                            $ss=array_sum(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                            if ($ss==0)
                            {
                                return $ssc;
                            }
                            return round($ss/$ssc,1);
                        });
                    }),
                    'detailed_attributes'=>$this->whenLoaded('detailed_attributes',function(){
                        return $this->when($this->detailed_attributes,function(){
                            return KeyValueResource::collection($this->detailed_attributes->load('translation'));
                        });
                    }),
                    'medias_url'=>$this->whenLoaded('medias',function(){
                        $urls=$this->medias->pluck('url')->toArray();
                        $result=[];
                        foreach ($urls as $url)
                        {
                            $result[]=url($url);
                        }
                        return $result;
                    }),
                    'medias'=>$this->whenLoaded('medias',function(){
                        return $this->when($this->medias,function(){
                            return MediaResource::collection($this->medias);
                        });
                    }),

                ];
            }
            if ($this->sub_category->main_category->id==$arade_id){
                return [
                    'id'=>$this->id,
                    'serial_number'=>$this->serial_number,
                                      'status'=>$this->status,
                    'advertisement_id'=>$this->whenLoaded('advertisement',function (){
                        return $this->advertisement->id;
                    }),
                    'date'=>Carbon::parse($this->advertisement->created_at)->format('Y-m-d H:i:s'),
                    'user_information'=>new UserResource($this->order->user->load(['region','roles'])),
                    'is_favorite'=>$user->favorite()->whereHasMorph(
                        'favoriteable',
                        ['App\Models\Property'], // تحدید المودیلات المسموحة
                        function ($query) {
                            $query->where('id', $this->id);
                        }
                    )->exists(),
                    'area'=>$this->area,
                    'price'=>$this->price_history_price,
                    'price_history'=>$this->price_history,
                    'publication_type'=>$this->publication_type,
                    'rent_type'=>$this->when($this->publication_type=="rent",function(){
                        return $this->rent_price_type;
                    }),
                    'address'=>$this->whenLoaded('region',function(){
                        return collect(new AddressResource($this->region->load('translation')))->put('secondary_address',$this->secondary_address);
                    }),
                    'ownership_type'=>$this->whenLoaded('ownership_type',function(){
                        return new IdNameResource($this->ownership_type->load('translation'));
                    }),
                    'main_category'=>new IdNameResource($this->sub_category->main_category->load('translation')),
                    'sub_category'=>new IdNameResource($this->sub_category->load('translation')),
//                    'description_e'=>$this->description,
                    'description'=>$this->when(($this->description
                        &&in_array("description",$attributes)),function(){
                        return $this->description;
                    }),
                    'age'=>$this->when(($this->age
                        &&in_array("age",$attributes)),function(){
                        return $this->age;
                    }),
                    'map_points'=>$this->when(($this->map_points
                        &&in_array("map_points",$attributes)),function(){
                        return json_decode($this->map_points);
                    }),
                    'pledge_type'=>$this->when(($this->pledge_type_id
                        &&in_array("pledge_type_id",$attributes)),function(){
                        return $this->whenLoaded('pledge_type',function() {
                            return new IdNameResource($this->pledge_type->load('translation'));
                        });
                    }),
                    'rooms_number'=>'-',
                    'rooms'=>$this->whenLoaded('rooms',function() use($attributes){
                        return $this->when(($this->rooms
                            &&in_array("rooms",$attributes)),function(){
                            return PropertyRoomResource::collection($this->rooms->load('room_type'));
                        });
                    }),
                    'directions'=>$this->whenLoaded('directions',function(){
                        return $this->when($this->directions,function(){
                            return IdTitleResource::collection($this->directions->load('translation'));
                        });
                    }),
                    'ownership_papers'=>$this->whenLoaded('ownership_papers',function(){
                        return $this->when($this->ownership_papers,function(){
                            return IdUrlResource::collection($this->ownership_papers);
                        });
                    }),
                    'features'=>$this->whenLoaded('features',function(){
                        return $this->when($this->features,function(){
                            return PropertyFeatureResource::collection($this->features->load('feature.translation'));
                        });
                    }),
                    'rating'=>$this->whenLoaded('ratings',function(){
                        return $this->when($this->ratings,function(){
                            $ssc=count(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                            $ss=array_sum(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                            if ($ss==0)
                            {
                                return $ssc;
                            }
                            return round($ss/$ssc,1);
                        });
                    }),
                    'detailed_attributes'=>$this->whenLoaded('detailed_attributes',function(){
                        return $this->when($this->detailed_attributes,function(){
                            return KeyValueResource::collection($this->detailed_attributes->load('translation'));
                        });
                    }),
                    'medias_url'=>$this->whenLoaded('medias',function(){
                        $urls=$this->medias->pluck('url')->toArray();
                        $result=[];
                        foreach ($urls as $url)
                        {
                            $result[]=url($url);
                        }
                        return $result;
                    }),
                    'medias'=>$this->whenLoaded('medias',function(){
                        return $this->when($this->medias,function(){
                            return MediaResource::collection($this->medias);
                        });
                    }),

                ];
            }
            return [
                'id'=>$this->id,
                'serial_number'=>$this->serial_number,
                                     'status'=>$this->status,
                'advertisement_id'=>$this->whenLoaded('advertisement',function (){
                    return $this->advertisement->id;
                }),
                'date'=>Carbon::parse($this->advertisement->created_at)->format('Y-m-d H:i:s'),
                'user_information'=>new UserResource($this->order->user->load(['region','roles'])),
                'is_favorite'=>$user->favorite()->whereHasMorph(
                    'favoriteable',
                    ['App\Models\Property'], // تحدید المودیلات المسموحة
                    function ($query) {
                        $query->where('id', $this->id);
                    }
                )->exists(),
                'area'=>$this->area,
                'price'=>$this->price_history_price,
                'price_history'=>$this->price_history,
                'publication_type'=>$this->publication_type,
                'rent_type'=>$this->when($this->publication_type=="rent",function(){
                    return $this->rent_price_type;
                }),
                'address'=>$this->whenLoaded('region',function(){
                    return collect(new AddressResource($this->region->load('translation')))->put('secondary_address',$this->secondary_address);
                }),
                'ownership_type'=>$this->whenLoaded('ownership_type',function(){
                    return new IdNameResource($this->ownership_type->load('translation'));
                }),
                'main_category'=>new IdNameResource($this->sub_category->main_category->load('translation')),
                'sub_category'=>new IdNameResource($this->sub_category->load('translation')),
//                'description_e'=>$this->description,
                'description'=>$this->when(($this->description
                    &&in_array("description",$attributes)),function(){
                    return $this->description;
                }),
                'age'=>$this->when(($this->age
                    &&in_array("age",$attributes)),function(){
                    return $this->age;
                }),
                'map_points'=>$this->when(($this->map_points
                    &&in_array("map_points",$attributes)),function(){
                    return json_decode($this->map_points);
                }),
                'pledge_type'=>$this->when(($this->pledge_type_id
                    &&in_array("pledge_type_id",$attributes)),function(){
                    return $this->whenLoaded('pledge_type',function() {
                        return new IdNameResource($this->pledge_type->load('translation'));
                    });
                }),
                'rooms_number'=>$this->whenLoaded('rooms',function() use($attributes){
                    return $this->when(($this->rooms
                        &&in_array("rooms",$attributes)),function(){
                        return $this->counter_rooms_number($this->rooms);
                    });
                }),
                'rooms'=>$this->whenLoaded('rooms',function() use($attributes){
                    return $this->when(($this->rooms
                        &&in_array("rooms",$attributes)),function(){
                        return PropertyRoomResource::collection($this->rooms->load('room_type'));
                    });
                }),
                'directions'=>$this->whenLoaded('directions',function(){
                    return $this->when($this->directions,function(){
                        return IdTitleResource::collection($this->directions->load('translation'));
                    });
                }),
                'ownership_papers'=>$this->whenLoaded('ownership_papers',function(){
                    return $this->when($this->ownership_papers,function(){
                        return IdUrlResource::collection($this->ownership_papers);
                    });
                }),
                'features'=>$this->whenLoaded('features',function(){
                    return $this->when($this->features,function(){
                        return PropertyFeatureResource::collection($this->features->load('feature.translation'));
                    });
                }),
                'rating'=>$this->whenLoaded('ratings',function(){
                    return $this->when($this->ratings,function(){
                        $ssc=count(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                        $ss=array_sum(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                        if ($ss==0)
                        {
                            return $ssc;
                        }
                        return round($ss/$ssc,1);
                    });
                }),
                'detailed_attributes'=>$this->whenLoaded('detailed_attributes',function(){
                    return $this->when($this->detailed_attributes,function(){
                        return KeyValueResource::collection($this->detailed_attributes->load('translation'));
                    });
                }),
                'medias_url'=>$this->whenLoaded('medias',function(){
                    $urls=$this->medias->pluck('url')->toArray();
                    $result=[];
                    foreach ($urls as $url)
                    {
                        $result[]=url($url);
                    }
                    return $result;
                }),
                'medias'=>$this->whenLoaded('medias',function(){
                    return $this->when($this->medias,function(){
                        return MediaResource::collection($this->medias);
                    });
                }),

            ];
        }
        if ($this->publication_type=="sale"){
            if ($this->sub_category->main_category->id==$arade_id){
                return [
                    'id'=>$this->id,
                    'serial_number'=>$this->serial_number,
                                         'status'=>$this->status,
                    'advertisement_id'=>$this->whenLoaded('advertisement',function (){
                        return $this->advertisement->id;
                    }),
                    'date'=>Carbon::parse($this->advertisement->created_at)->format('Y-m-d H:i:s'),
                    'user_information'=>new UserResource($this->order->user->load(['region','roles'])),
                    'is_favorite'=>false,
                    'area'=>$this->area,
                    'price'=>$this->price_history_price,
                    'price_history'=>$this->price_history,
                    'publication_type'=>$this->publication_type,
                    'rent_type'=>" ",
                    'address'=>$this->whenLoaded('region',function(){
                        return collect(new AddressResource($this->region->load('translation')))->put('secondary_address',$this->secondary_address);
                    }),
                    'ownership_type'=>$this->whenLoaded('ownership_type',function(){
                        return new IdNameResource($this->ownership_type->load('translation'));
                    }),
                    'main_category'=>new IdNameResource($this->sub_category->main_category->load('translation')),
                    'sub_category'=>new IdNameResource($this->sub_category->load('translation')),
//                    'description_e'=>$this->description,
                    'description'=>$this->when(($this->description
                        &&in_array("description",$attributes)),function(){
                        return $this->description;
                    }),
                    'age'=>$this->when(($this->age
                        &&in_array("age",$attributes)),function(){
                        return $this->age;
                    }),
                    'map_points'=>$this->when(($this->map_points
                        &&in_array("map_points",$attributes)),function(){
                        return json_decode($this->map_points);
                    }),
                    'pledge_type'=>$this->when(($this->pledge_type_id
                        &&in_array("pledge_type_id",$attributes)),function(){
                        return $this->whenLoaded('pledge_type',function() {
                            return new IdNameResource($this->pledge_type->load('translation'));
                        });
                    }),
                    'rooms_number'=>'-',
                    'rooms'=>$this->whenLoaded('rooms',function() use($attributes){
                        return $this->when(($this->rooms
                            &&in_array("rooms",$attributes)),function(){
                            return PropertyRoomResource::collection($this->rooms->load('room_type'));
                        });
                    }),
                    'directions'=>$this->whenLoaded('directions',function(){
                        return $this->when($this->directions,function(){
                            return IdTitleResource::collection($this->directions->load('translation'));
                        });
                    }),
                    'ownership_papers'=>$this->whenLoaded('ownership_papers',function(){
                        return $this->when($this->ownership_papers,function(){
                            return IdUrlResource::collection($this->ownership_papers);
                        });
                    }),
                    'features'=>$this->whenLoaded('features',function(){
                        return $this->when($this->features,function(){
                            return PropertyFeatureResource::collection($this->features->load('feature.translation'));
                        });
                    }),
                    'rating'=>$this->whenLoaded('ratings',function(){
                        return $this->when($this->ratings,function(){
                            $ssc=count(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                            $ss=array_sum(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                            if ($ss==0)
                            {
                                return $ssc;
                            }
                            return round($ss/$ssc,1);
                        });
                    }),
                    'detailed_attributes'=>$this->whenLoaded('detailed_attributes',function(){
                        return $this->when($this->detailed_attributes,function(){
                            return KeyValueResource::collection($this->detailed_attributes->load('translation'));
                        });
                    }),
                    'medias_url'=>$this->whenLoaded('medias',function(){
                        $urls=$this->medias->pluck('url')->toArray();
                        $result=[];
                        foreach ($urls as $url)
                        {
                            $result[]=url($url);
                        }
                        return $result;
                    }),
                    'medias'=>$this->whenLoaded('medias',function(){
                        return $this->when($this->medias,function(){
                            return MediaResource::collection($this->medias);
                        });
                    }),

                ];
            }
            return [
                'id'=>$this->id,
                'serial_number'=>$this->serial_number,
                                     'status'=>$this->status,
                'advertisement_id'=>$this->whenLoaded('advertisement',function (){
                    return $this->advertisement->id;
                }),
                'date'=>Carbon::parse($this->advertisement->created_at)->format('Y-m-d H:i:s'),
                'user_information'=>new UserResource($this->order->user->load(['region','roles'])),
                'is_favorite'=>false,
                'area'=>$this->area,
                'price'=>$this->price_history_price,
                'price_history'=>$this->price_history,
                'publication_type'=>$this->publication_type,
                'rent_type'=>" ",
                'address'=>$this->whenLoaded('region',function(){
                    return collect(new AddressResource($this->region->load('translation')))->put('secondary_address',$this->secondary_address);
                }),
                'ownership_type'=>$this->whenLoaded('ownership_type',function(){
                    return new IdNameResource($this->ownership_type->load('translation'));
                }),
                'main_category'=>new IdNameResource($this->sub_category->main_category->load('translation')),
                'sub_category'=>new IdNameResource($this->sub_category->load('translation')),
//                'description'=>$this->description,
//                'description_e'=>$this->description,
                'description'=>$this->when(($this->description
                    &&in_array("description",$attributes)),function(){
                    return $this->description;
                }),
                'age'=>$this->when(($this->age
                    &&in_array("age",$attributes)),function(){
                    return $this->age;
                }),
                'map_points'=>$this->when(($this->map_points
                    &&in_array("map_points",$attributes)),function(){
                    return json_decode($this->map_points);
                }),
                'pledge_type'=>$this->when(($this->pledge_type_id
                    &&in_array("pledge_type_id",$attributes)),function(){
                    return $this->whenLoaded('pledge_type',function() {
                        return new IdNameResource($this->pledge_type->load('translation'));
                    });
                }),
                'rooms_number'=>$this->whenLoaded('rooms',function() use($attributes){
                    return $this->when(($this->rooms
                        &&in_array("rooms",$attributes)),function(){
                        return $this->counter_rooms_number($this->rooms);
                    });
                }),
                'rooms'=>$this->whenLoaded('rooms',function() use($attributes){
                    return $this->when(($this->rooms
                        &&in_array("rooms",$attributes)),function(){
                        return PropertyRoomResource::collection($this->rooms->load('room_type'));
                    });
                }),
                'directions'=>$this->whenLoaded('directions',function(){
                    return $this->when($this->directions,function(){
                        return IdTitleResource::collection($this->directions->load('translation'));
                    });
                }),
                'ownership_papers'=>$this->whenLoaded('ownership_papers',function(){
                    return $this->when($this->ownership_papers,function(){
                        return IdUrlResource::collection($this->ownership_papers);
                    });
                }),
                'features'=>$this->whenLoaded('features',function(){
                    return $this->when($this->features,function(){
                        return PropertyFeatureResource::collection($this->features->load('feature.translation'));
                    });
                }),
                'rating'=>$this->whenLoaded('ratings',function(){
                    return $this->when($this->ratings,function(){
                        $ssc=count(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                        $ss=array_sum(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                        if ($ss==0)
                        {
                            return $ssc;
                        }
                        return round($ss/$ssc,1);
                    });
                }),
                'detailed_attributes'=>$this->whenLoaded('detailed_attributes',function(){
                    return $this->when($this->detailed_attributes,function(){
                        return KeyValueResource::collection($this->detailed_attributes->load('translation'));
                    });
                }),
                'medias_url'=>$this->whenLoaded('medias',function(){
                    $urls=$this->medias->pluck('url')->toArray();
                    $result=[];
                    foreach ($urls as $url)
                    {
                        $result[]=url($url);
                    }
                    return $result;
                }),
                'medias'=>$this->whenLoaded('medias',function(){
                    return $this->when($this->medias,function(){
                        return MediaResource::collection($this->medias);
                    });
                }),

            ];
        }
        if ($this->sub_category->main_category->id==$arade_id){
            return [
                'id'=>$this->id,
                'serial_number'=>$this->serial_number,
                                     'status'=>$this->status,
                'advertisement_id'=>$this->whenLoaded('advertisement',function (){
                    return $this->advertisement->id;
                }),
                'date'=>Carbon::parse($this->advertisement->created_at)->format('Y-m-d H:i:s'),
                'user_information'=>new UserResource($this->order->user->load(['region','roles'])),
                'is_favorite'=>false,
                'area'=>$this->area,
                'price'=>$this->price_history_price,
                'price_history'=>$this->price_history,
                'publication_type'=>$this->publication_type,
                'rent_type'=>$this->when($this->publication_type=="rent",function(){
                    return $this->rent_price_type;
                }),
                'address'=>$this->whenLoaded('region',function(){
                    return collect(new AddressResource($this->region->load('translation')))->put('secondary_address',$this->secondary_address);
                }),
                'ownership_type'=>$this->whenLoaded('ownership_type',function(){
                    return new IdNameResource($this->ownership_type->load('translation'));
                }),
                'main_category'=>new IdNameResource($this->sub_category->main_category->load('translation')),
                'sub_category'=>new IdNameResource($this->sub_category->load('translation')),
//                'description_e'=>$this->description,
                'description'=>$this->when(($this->description
                    &&in_array("description",$attributes)),function(){
                    return $this->description;
                }),
                'age'=>$this->when(($this->age
                    &&in_array("age",$attributes)),function(){
                    return $this->age;
                }),
                'map_points'=>$this->when(($this->map_points
                    &&in_array("map_points",$attributes)),function(){
                    return json_decode($this->map_points);
                }),
                'pledge_type'=>$this->when(($this->pledge_type_id
                    &&in_array("pledge_type_id",$attributes)),function(){
                    return $this->whenLoaded('pledge_type',function() {
                        return new IdNameResource($this->pledge_type->load('translation'));
                    });
                }),
                'rooms_number'=>'-',
                'rooms'=>$this->whenLoaded('rooms',function() use($attributes){
                    return $this->when(($this->rooms
                        &&in_array("rooms",$attributes)),function(){
                        return PropertyRoomResource::collection($this->rooms->load('room_type'));
                    });
                }),
                'directions'=>$this->whenLoaded('directions',function(){
                    return $this->when($this->directions,function(){
                        return IdTitleResource::collection($this->directions->load('translation'));
                    });
                }),
                'ownership_papers'=>$this->whenLoaded('ownership_papers',function(){
                    return $this->when($this->ownership_papers,function(){
                        return IdUrlResource::collection($this->ownership_papers);
                    });
                }),
                'features'=>$this->whenLoaded('features',function(){
                    return $this->when($this->features,function(){
                        return PropertyFeatureResource::collection($this->features->load('feature.translation'));
                    });
                }),
                'rating'=>$this->whenLoaded('ratings',function(){
                    return $this->when($this->ratings,function(){
                        $ssc=count(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                        $ss=array_sum(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                        if ($ss==0)
                        {
                            return $ssc;
                        }
                        return round($ss/$ssc,1);
                    });
                }),
                'detailed_attributes'=>$this->whenLoaded('detailed_attributes',function(){
                    return $this->when($this->detailed_attributes,function(){
                        return KeyValueResource::collection($this->detailed_attributes->load('translation'));
                    });
                }),
                'medias_url'=>$this->whenLoaded('medias',function(){
                    $urls=$this->medias->pluck('url')->toArray();
                    $result=[];
                    foreach ($urls as $url)
                    {
                        $result[]=url($url);
                    }
                    return $result;
                }),
                'medias'=>$this->whenLoaded('medias',function(){
                    return $this->when($this->medias,function(){
                        return MediaResource::collection($this->medias);
                    });
                }),

            ];
        }
        return [
            'id'=>$this->id,
            'serial_number'=>$this->serial_number,
                                 'status'=>$this->status,
            'advertisement_id'=>$this->whenLoaded('advertisement',function (){
                return $this->advertisement->id;
            }),
            'date'=>Carbon::parse($this->advertisement->created_at)->format('Y-m-d H:i:s'),
            'user_information'=>new UserResource($this->order->user->load(['region','roles'])),
            'is_favorite'=>false,
            'area'=>$this->area,
            'price'=>$this->price_history_price,
            'price_history'=>$this->price_history,
            'publication_type'=>$this->publication_type,
            'rent_type'=>$this->when($this->publication_type=="rent",function(){
                return $this->rent_price_type;
            }),
            'address'=>$this->whenLoaded('region',function(){
                return collect(new AddressResource($this->region->load('translation')))->put('secondary_address',$this->secondary_address);
            }),
            'ownership_type'=>$this->whenLoaded('ownership_type',function(){
                return new IdNameResource($this->ownership_type->load('translation'));
            }),
            'main_category'=>new IdNameResource($this->sub_category->main_category->load('translation')),
            'sub_category'=>new IdNameResource($this->sub_category->load('translation')),
//            'description_e'=>$this->description,
            'description'=>$this->when(($this->description
                &&in_array("description",$attributes)),function(){
                return $this->description;
            }),
            'age'=>$this->when(($this->age
                &&in_array("age",$attributes)),function(){
                return $this->age;
            }),
            'map_points'=>$this->when(($this->map_points
                &&in_array("map_points",$attributes)),function(){
                return json_decode($this->map_points);
            }),
            'pledge_type'=>$this->when(($this->pledge_type_id
                &&in_array("pledge_type_id",$attributes)),function(){
                return $this->whenLoaded('pledge_type',function() {
                    return new IdNameResource($this->pledge_type->load('translation'));
                });
            }),
            'rooms_number'=>$this->whenLoaded('rooms',function() use($attributes){
                return $this->when(($this->rooms
                    &&in_array("rooms",$attributes)),function(){
                    return $this->counter_rooms_number($this->rooms);
                });
            }),
            'rooms'=>$this->whenLoaded('rooms',function() use($attributes){
                return $this->when(($this->rooms
                    &&in_array("rooms",$attributes)),function(){
                    return PropertyRoomResource::collection($this->rooms->load('room_type'));
                });
            }),
            'directions'=>$this->whenLoaded('directions',function(){
                return $this->when($this->directions,function(){
                    return IdTitleResource::collection($this->directions->load('translation'));
                });
            }),
            'ownership_papers'=>$this->whenLoaded('ownership_papers',function(){
                return $this->when($this->ownership_papers,function(){
                    return IdUrlResource::collection($this->ownership_papers);
                });
            }),
            'features'=>$this->whenLoaded('features',function(){
                return $this->when($this->features,function(){
                    return PropertyFeatureResource::collection($this->features->load('feature.translation'));
                });
            }),
            'rating'=>$this->whenLoaded('ratings',function(){
                return $this->when($this->ratings,function(){
                    $ssc=count(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                    $ss=array_sum(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                    if ($ss==0)
                    {
                        return $ssc;
                    }
                    return round($ss/$ssc,1);
                });
            }),
            'detailed_attributes'=>$this->whenLoaded('detailed_attributes',function(){
                return $this->when($this->detailed_attributes,function(){
                    return KeyValueResource::collection($this->detailed_attributes->load('translation'));
                });
            }),
            'medias_url'=>$this->whenLoaded('medias',function(){
                $urls=$this->medias->pluck('url')->toArray();
                $result=[];
                foreach ($urls as $url)
                {
                    $result[]=url($url);
                }
                return $result;
            }),
            'medias'=>$this->whenLoaded('medias',function(){
                return $this->when($this->medias,function(){
                    return MediaResource::collection($this->medias);
                });
            }),

        ];
    }
    public function counter_rooms_number($rooms)
    {
        $counter=0;
        $ids=RoomType::whereIn('name', ['مطبخ', 'حمام'])
            ->pluck('id')->toArray();
        foreach ($rooms as $room){
            if (!in_array($room->room_type->id,$ids)){
                $counter+=$room->count;
            }
        }
        return strval($counter);
    }
}
