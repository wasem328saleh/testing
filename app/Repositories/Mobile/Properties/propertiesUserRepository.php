<?php

namespace App\Repositories\Mobile\Properties;

use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\ConfigAttributesResource;
use App\Http\Resources\IdNameResource;
use App\Http\Resources\IdTitleResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PropertyCategorisResource;
use App\Http\Resources\PropertyResource;
use App\Jobs\SendNotification;
use App\Models\Advertisement;
use App\Models\AdvertisingPackage;
use App\Models\City;
use App\Models\Classification;
use App\Models\ConfigAttribute;
use App\Models\Direction;
use App\Models\Media;
use App\Models\Order;
use App\Models\OwnershipType;
use App\Models\PledgeType;
use App\Models\Property;
use App\Models\PropertyMainCategory;
use App\Models\PropertySubCategory;
use App\Models\RoomType;
use App\Models\Subscribe;
use App\Models\User;
use App\Rules\RelationshipExists;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class propertiesUserRepository implements PropertiesUserRepositoryInterface
{
use GeneralTrait;

    public function add_detailed_attributes($request,$property,$category_id)
    {
        $attributes=collect(ConfigAttributesResource::collection(ConfigAttribute::select('attribute_name')
            ->where('category_id',$category_id)
            ->where('is_required',true)
            ->get()))
            ->pluck('attribute_name')
            ->toArray();

        foreach ($attributes as $attribute) {
            if ($request->$attribute !=null&&$attribute!='pledge_type_id'&&$attribute!='rooms') {
            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ',$attribute),
                'value'=>request()->$attribute,
            ]);
            }

        }
    }
    public function edit_detailed_attributes($request,$property,$category_id)
    {
        $attributes=collect(ConfigAttributesResource::collection(ConfigAttribute::select('attribute_name')
            ->where('category_id',$category_id)
            ->where('is_required',true)
            ->get()))
            ->pluck('attribute_name')
            ->toArray();

        foreach ($attributes as $attribute) {
            if ($request->$attribute !=null&&$attribute!='pledge_type_id'&&$attribute!='rooms') {
                $property->detailed_attributes()
                    ->where('key',Str::replace('_',' ',$attribute))
                    ->first()
                    ->update([
                        'value'=>request()->$attribute
                    ]);
            }
        }
    }
    public function add_property($request)
    {
        // TODO: Implement add_property() method.

        try {
            DB::beginTransaction();
            $user=Auth::user();
        $classification_id=request()->classification_id;
        $serial_number=$this->generate_serial_number(Property::class,$classification_id);
            if (request()->subscribe_id!=null){
                $subscribe=Subscribe::where('id',request()->subscribe_id)->first();
            }
        if (request()->subscribe_id==null){
            $subscribe=$user->subscribes()->where('active',true)
                ->whereColumn('used_advertisements_count','<','advertisements_count')
                ->orderBy('created_at','asc')
                ->first();
            if ($subscribe == null) {
                $package_id=1;
                $package=AdvertisingPackage::where('id',$package_id)->first();
                $subscription_start_date=Carbon::now()->toDate();
                $subscription_end_date=Carbon::now()->addDays($package->validity_period)->toDate();
                $subscribe=$user->subscribes()->create([
                    'subscription_start_date'=>$subscription_start_date,
                    'subscription_end_date'=>$subscription_end_date,
                    'advertisements_count'=>999999,
                    'advertising_package_id'=>$package_id
                ]);
//                return $this->returnError(100,'Not Found Activate Subscription,Please Subscript to Advertisements Package');
            }
        }

            $existingAttributes  = $this->config_attributes(intval($classification_id),intval(request()->sub_category_id??request()->sub_category_id));
            $attributes_name=[];
            foreach ($existingAttributes as $attribute){
                $attributes_name[]=$attribute['attribute_name'];
            }
            if (request()->has('identification_papers')&&Auth::user()->roles()->first()->title=='user'){
                $identification_papers=request()->identification_papers;
                foreach ($identification_papers as $identification_paper) {
                    $user->personal_identification_papers()->create([
                        'url'=>$this->UploadeImage('identification_paper',$identification_paper)
                    ]);
                }
            }

        $property=new Property();
        $property->serial_number=$serial_number;
        $publication_type=request()->publication_type;
        $price=request()->price;
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
        $property->publication_type=$publication_type;
        if ($publication_type==='sale'){
            $property->sale_price=$price;
        }elseif ($publication_type==='rent'){
            $rent_type=request()->rent_type;
            $rent_price=['type'=>$rent_type,'price'=>intval($price)];
            $property->rent_price=$rent_price;
        }
        if (request()->area !== null){
            $property->area=request()->area;
        }
        if (request()->age !== null){
                $property->age=request()->age;
        }
        if (request()->map_points !== null){
            $property->map_points=json_encode(request()->map_points);
        }
        if (request()->secondary_address !== null){
                $property->secondary_address=request()->secondary_address;
        }
        $property->region_id=request()->region_id;
        $property->category_id=request()->sub_category_id;
        if (request()->ownership_type_id !== null){
            $property->ownership_type_id=request()->ownership_type_id;
        }
        if (request()->pledge_type_id !== null&&in_array("pledge_type_id",$attributes_name)){
                $property->pledge_type_id=request()->pledge_type_id;
        }
        $property->save();
        if ($request->hasFile('medias')) {
            $medias = $request->file('medias');
            foreach ($medias as $media) {
             $type = $media->getClientOriginalExtension();
             $size = $media->getSize();
             $url=$this->UploadeImage('properties_media',$media);
             $property->medias()->create([
                 'url'=>$url,
                 'type'=>$type,
                 'size'=>$size
             ]);
            }
        }
            if ($request->hasFile('ownership_papers')) {
                $ownership_papers = $request->file('ownership_papers');
                foreach ($ownership_papers as $paper) {
                    $url=$this->UploadeImage('properties_ownership_papers',$paper);
                    $property->ownership_papers()->create([
                        'url'=>$url
                    ]);
                }
            }
            if ($request->rooms !== null&&in_array("rooms",$attributes_name)){
                $rooms=$request->rooms;
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            if ($request->directions !== null){
                $directions=$request->directions;
                $property->directions()->sync($directions);
            }
            if ($request->features !== null){
                $features=$request->features;
                foreach ($features as $feature) {
                    $property->features()->create([
                        'feature_id'=>$feature
                    ]);
                }
            }
            $this->add_detailed_attributes($request,$property,request()->sub_category_id);
        $property->advertisement()->create([
            'advertisement_start_date'=>Carbon::now()->toDate(),
            'advertisement_end_date'=>Carbon::now()
                                      ->addDays($subscribe->advertising_package
                                                          ->validity_period_per_advertisement)
                                      ->toDate(),
            'subscribe_id'=>$subscribe->id,
            'classification_id'=>$classification_id
        ]);
        $subscribe->used_advertisements_count+=1;
        if ($subscribe->used_advertisements_count===$subscribe->advertisements_count)
        {
            $subscribe->active=false;
        }
        $subscribe->save();
        $property->order()->create([
            'serial_number'=>$this->generate_serial_number(Order::class,$classification_id),
            'date'=>Carbon::now()->toDate(),
            'user_id'=>$user->id,
            'classification_id'=>$classification_id
        ]);

            //send notifications to super admin and admin
            $title = trans('notifications.send_order_add_property');
            $body =trans('notifications.send_order_add_property_body') . " : " . $property->serial_number;
            $type = 'info';
            $users=User::with(['device_tokens','notifications'])
                ->whereHas('roles',function($q){
                    $q->where(function ($query){
                        $query->where('title','super_admin')
                            ->orWhere('title','admin');
                    });
                })->get();

            $data=[
                'users'=>$users,
                'title'=>$title."111",
                'body'=>$body,
                'type'=>$type
            ];
//            return $data['users'];
            dispatch(new SendNotification($data));
        DB::commit();
        return $this->returnSuccessMessage('Stored Property Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_my_properties()
    {
        // TODO: Implement get_my_properties() method.
        $user=Auth::user();
        $all=OrderResource::collection($user->orders()->with('orderable')
            ->whereHasMorph(
                'orderable',
                ['App\Models\Property']
            )
            ->get());
        return $this->returnData('my_advertising',$all);
    }

    public function edited_my_property($request)
    {
        // TODO: Implement edited_my_property() method.
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $property_id=request()->property_id;
            $property=Property::where('id',$property_id)->first();
            $exists=$this->relationship_exists($user->id,$property->order->id,'orders',User::class);

            if ($exists){
                $price=0;
                $priceHistory = $property->price_history;
                if (request()->price)
                {
                    $new_price=intval(request()->price);
                    $h_pp=$property->price_history_price;
                    $property->update(
                        [
                            'price_history'=>[
                                'price'=>$new_price,
                                'history'=>$h_pp
                            ]
                        ]
                    );
                    $price=$new_price;
                }else{
                    $price=$property->price_history_price;
                }
                $publication_type=request()->publication_type?request()->publication_type:$property->publication_type;
                if ($publication_type==='sale'){
                    $property->sale_price=$price;
                }elseif ($publication_type==='rent'){
                    $rent_type='';
                    if (request()->rent_type){
                        $rent_type=request()->rent_type;
                    }else{
                        $rent_type=$property->rent_type;
                    }
                    $rent_price=['type'=>$rent_type,'price'=>$price];
                    $property->rent_price=$rent_price;
                }
                if (request()->area !== null){
                    $property->area=request()->area;
                }
                if (request()->ownership_type_id !== null){
                    $property->ownership_type_id=request()->ownership_type_id;
                }
                if (request()->description !== null){
                    $property->description=request()->description;
                }
                if (request()->age !== null){
                    $property->age=request()->age;
                }
                if (request()->map_points !== null){
                    $property->map_points=json_encode(request()->map_points);
                }
                if (request()->secondary_address !== null){
                    $property->secondary_address=request()->secondary_address;
                }
                if (request()->region_id !== null){
                    $property->region_id=request()->region_id;
                }
                $existingAttributes=[];
                $classification_id=Classification::where('name','properties')->first()->id;
                $sub_category_id=0;
                if (request()->sub_category_id !== null){
                    $sub_category_id=request()->sub_category_id;
                    $existingAttributes  = $this->config_attributes(intval($classification_id),intval(request()->sub_category_id));
                    $property->category_id=request()->sub_category_id;
                }else{
                    $sub_category_id=$property->sub_category_id;
                    $existingAttributes  = $this->config_attributes(intval($classification_id),intval($property->sub_category_id));
                }
                $attributes_name=[];
                foreach ($existingAttributes as $attribute){
                    $attributes_name[]=$attribute['attribute_name'];
                }
                if (request()->pledge_type_id !== null&&in_array("pledge_type_id",$attributes_name)){
                    $property->pledge_type_id=request()->pledge_type_id;
                }
                $property->save();
                if ($request->rooms !== null&&in_array("rooms",$attributes_name)){
                    $rooms=$request->rooms;
                    foreach ($rooms as $room) {
                        $id=$room['id'];
                        $room_number=$room['number'];
                        $property->rooms()->where('id',$id)->update([
                            'count'=>$room_number
                        ]);
                    }
                }
                if ($request->directions !== null){
                    $directions=$request->directions;
                    $property->directions()->sync($directions);
                }
                if ($request->features !== null){
                    $property->features()->delete();
                    $features=$request->features;
                    foreach ($features as $feature) {
                        $property->features()->create([
                            'feature_id'=>$feature
                        ]);
                    }
                }
                $this->edit_detailed_attributes($request,$property,$sub_category_id);
                DB::commit();
                return $this->returnSuccessMessage('Updated Property Successfully');
            }
            DB::rollBack();
            return $this->returnError(404,'Working Error');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function deleted_my_property($request)
    {
        // TODO: Implement deleted_my_property() method.
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $property_id=request()->property_id;
            $property=Property::where('id',$property_id)->first();
            $exists=$this->relationship_exists($user->id,$property->order->id,'orders',User::class);
            if ($exists){
                $property->delete();
                DB::commit();
                return $this->returnSuccessMessage('Deleted Property Successfully');
            }
            DB::rollBack();
            return $this->returnError(404,'Working Error');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function edited_activation_my_property($request)
    {
        // TODO: Implement edited_activation_my_property() method.
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $property_id=request()->property_id;
            $property=Property::where('id',$property_id)->first();
            $exists=$this->relationship_exists($user->id,$property->order->id,'orders',User::class);
            if ($exists){
                $order_status=$property->order->status;
                if (Str::contains($order_status,'posted'))
                {
                    if ($property->status=="active"){
                        $property->status="inactive";
                        $property->save();
                        DB::commit();
                        return $this->returnSuccessMessage('Inactivated Property Successfully');
                    }elseif ($property->status=="inactive"){
                        $property->status="active";
                        $property->save();
                        DB::commit();
                        return $this->returnSuccessMessage('Activated Property Successfully');
                    }
                }

            }
            DB::rollBack();
            return $this->returnError(404,'Working Error');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_all_properties($city_id=null)
    {
        // TODO: Implement get_all_properties() method.
        if ($city_id==null){
            $all=AdvertisementResource::collection(Advertisement::with(['advertisementable','subscribe.user'])
                ->where('active',true)
                ->orderByDesc('updated_at')
                ->get());
            return $this->returnData('advertising',$all);
        }
        $all=AdvertisementResource::collection(Advertisement::with(['advertisementable','subscribe.user'])
            ->where('active',true)
            ->whereHasMorph(
                'advertisementable',
                ['App\Models\Property'], // تحدید المودیلات المسموحة
                function ($query) use ($city_id) {
                    $query->whereHas('region',function($q) use ($city_id){
                        $q->where('city_id',$city_id);
                    });
                }
            )
            ->orderByDesc('updated_at')
            ->get());
        return $this->returnData('advertising',$all);
    }

    public function get_all_properties_sale($city_id=null)
    {
        // TODO: Implement get_all_properties() method.
        if ($city_id==null){
            $all=AdvertisementResource::collection(Advertisement::with(['advertisementable','subscribe.user'])
                ->where('active',true)
                ->whereHas('advertisementable',function ($query){
                    $query->where('publication_type','sale');
                })
                ->orderByDesc('updated_at')
                ->get());
            return $this->returnData('advertising',$all);
        }
        $all=AdvertisementResource::collection(Advertisement::with(['advertisementable','subscribe.user'])
            ->where('active',true)
            ->whereHas('advertisementable',function ($query){
                $query->where('publication_type','sale');
            })
            ->whereHasMorph(
                'advertisementable',
                ['App\Models\Property'], // تحدید المودیلات المسموحة
                function ($query) use ($city_id) {
                    $query->whereHas('region',function($q) use ($city_id){
                        $q->where('city_id',$city_id);
                    });
                }
            )
            ->orderByDesc('updated_at')
            ->get());
        return $this->returnData('advertising',$all);
    }

    public function get_all_properties_rent($city_id=null)
    {
        // TODO: Implement get_all_properties() method.
        if ($city_id==null){
            $all=AdvertisementResource::collection(Advertisement::with(['advertisementable','subscribe.user'])
                ->where('active',true)
                ->whereHas('advertisementable',function ($query){
                    $query->where('publication_type','rent');
                })
                ->orderByDesc('updated_at')
                ->get());
            return $this->returnData('advertising',$all);
        }
        $all=AdvertisementResource::collection(Advertisement::with(['advertisementable','subscribe.user'])
            ->where('active',true)
            ->whereHas('advertisementable',function ($query){
                $query->where('publication_type','rent');
            })
            ->whereHasMorph(
                'advertisementable',
                ['App\Models\Property'], // تحدید المودیلات المسموحة
                function ($query) use ($city_id) {
                    $query->whereHas('region',function($q) use ($city_id){
                        $q->where('city_id',$city_id);
                    });
                }
            )
            ->orderByDesc('updated_at')
            ->get());
        return $this->returnData('advertising',$all);
    }

    public function search_filter_properties($request)
    {
        // TODO: Implement search_filter_properties() method.
        try {
            $properties=Property::query()
                ->select([
                    'id',
                    'serial_number',
                    'area',
                    'price_history',
                    'publication_type',
                    'rent_price',
                    'age',
                    'status',
                    'secondary_address',
//                    'deleted_at',
                    'map_points',
                    'region_id',
                    'ownership_type_id',
                    'pledge_type_id',
                    'category_id',
                    'price_history_price',
                    'rent_price_type',
                    'description'
                ])
                ->with([
                    'advertisement',
                    'ownership_type',
                    'pledge_type',
                    'sub_category.main_category',
                    'region.city.country',
                    'features.feature',
                    'directions',
                    'rooms.room_type',
                    'ratings',
                    'detailed_attributes',
                    'medias',
                    'order'
                ])
                ->where('status','active')
                ->when(isset(request()->publication_type), function ($q) use ($request) {
                    $q->where('publication_type', request()->publication_type);
                })
                ->when(isset(request()->main_category_id), function ($q) use ($request) {
                    $q->whereHas('sub_category', function ($q) use ($request) {
                        $q->where('main_category_id', request()->main_category_id);
                    });
                })
                ->when(isset(request()->sub_category_id), function ($q) use ($request) {
                    $q->where('category_id', request()->sub_category_id);
                })
                ->when(isset(request()->min_area), function ($q) use ($request) {
                    $q->where('area', '>=', request()->min_area);
                })
                ->when(isset(request()->max_area), function ($q) use ($request) {
                    $q->where('area', '<=', request()->max_area);
                })
                ->when(isset(request()->ownership_type_id), function ($q) use ($request) {
                    $q->where('ownership_type_id', request()->ownership_type_id);
                })
                ->when(isset(request()->min_price), function ($q) use ($request) {
                    $q->where('price_history_price', '>=', request()->min_price);
                })
                ->when(isset(request()->max_price), function ($q) use ($request) {
                    $q->where('price_history_price', '<=', request()->max_price);
                })
                ->when(isset(request()->country_id), function ($q) use ($request) {
                    $q->whereHas('region.city', function ($q) use ($request) {
                        $q->where('country_id', request()->country_id);
                    });
                })
                ->when(isset(request()->city_id), function ($q) use ($request) {
                    $q->whereHas('region', function ($q) use ($request) {
                        $q->where('city_id', request()->city_id);
                    });
                })
                ->when(isset(request()->region_id), function ($q) use ($request) {
                    $q->where('region_id', request()->region_id);
                })
                ->when(isset(request()->pledge_type_id), function ($q) use ($request) {
                    $q->where('pledge_type_id', request()->pledge_type_id);
                })
                ->when(isset(request()->min_age), function ($q) use ($request) {
                    $q->where('age', '>=', request()->min_age);
                })
                ->when(isset(request()->max_age), function ($q) use ($request) {
                    $q->where('age', '<=', request()->max_age);
                })
                ->when(isset(request()->secondary_address), function ($q) use ($request) {
                    $q->whereRaw("MATCH(secondary_address) AGAINST(? IN BOOLEAN MODE)", [request()->secondary_address]);
                })
                ->when(isset(request()->features) && !empty(request()->features), function ($q) use ($request) {
                    $q->whereHas('features', function ($q) use ($request) {
                        $q->whereIn('feature_id', request()->features);
                    });
                })
                ->when(isset(request()->directions) && !empty(request()->directions), function ($q) use ($request) {
                    $q->whereHas('directions', function ($q) use ($request) {
                        $q->whereIn('directions.id', request()->directions);
                    });
                })
                ->when(isset(request()->rent_type) && request()->rent_type, function ($q) use ($request) {
                    $q->where('rent_price_type', request()->rent_type);
                })
                ->when(isset(request()->detailed_attributes)&& !empty(request()->detailed_attributes), function ($q) use ($request) {
                    $q->whereHas('detailed_attributes', function ($q) use ($request) {
                        $q->where(function ($sub) use ($request) {
                            foreach (request()->detailed_attributes as $key => $value) {
                                $sub->orWhere([
                                    'key' => $key,
                                    'value' => $value
                                ]);
                            }
                        });
                    });
                })
                ->when(isset(request()->rooms)&& !empty(request()->rooms), function ($q) use ($request) {
                    $q->whereHas('rooms', function ($q) use ($request) {
                        $q->where(function ($sub) use ($request) {
                            foreach (request()->rooms as $room) {
                                $sub->where([
                                    'room_type_id' => $room['type_id'],
                                    'count' => $room['number']
                                ]);
                            }
                        });
                    });
                })
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('advertisements')
                        ->whereColumn('advertisements.advertisementable_id', 'properties.id')
                        ->where('advertisements.advertisementable_type', Property::class);
                })
                ->when(isset(request()->is_merchant),function ($q)use ($request){
                    if (request()->is_merchant){
                        $q->whereHas('order.user.roles', function ($query) {
                            $query->whereIn('roles.title',['super_admin','merchant']); // استخدام title بدلاً من name
                        })
                            ->with(['order.user.roles' => function ($query) {
                                $query->select('roles.id', 'roles.title as name'); // استخدام alias
                            }]);
                    }else{
                        $q->whereHas('order.user.roles', function ($query) {
                            $query->whereIn('roles.title',['super_admin','user']); // استخدام title بدلاً من name
                        })
                            ->with(['order.user.roles' => function ($query) {
                                $query->select('roles.id', 'roles.title as name'); // استخدام alias
                            }]);
                    }

                })
//                ->whereHas('order.user.roles', function ($query) {
//                    $query->where('roles.title', 'merchant'); // استخدام title بدلاً من name
//                })
//                ->with(['order.user.roles' => function ($query) {
//                    $query->select('roles.id', 'roles.title as name'); // استخدام alias
//                }])
                ->orderByDesc('updated_at')
                ->get();
            // ->paginate(25);
            return $this->returnData('advertising',PropertyResource::collection($properties));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

//     public function search_filter_properties($request)
//     {
//         // TODO: Implement search_filter_properties() method.
//         try {
//             $properties=Property::query()
//                 ->select([
//                     'id',
//                     'serial_number',
//                     'area',
//                     'price_history',
//                     'publication_type',
//                     'rent_price',
//                     'age',
//                     'status',
//                     'secondary_address',
// //                    'deleted_at',
//                     'map_points',
//                     'region_id',
//                     'ownership_type_id',
//                     'pledge_type_id',
//                     'category_id',
//                     'price_history_price',
//                     'rent_price_type',
//                 ])
//                 ->with([
//                     'advertisement',
//                     'ownership_type',
//                     'pledge_type',
//                     'sub_category.main_category',
//                     'region.city.country',
//                     'features.feature',
//                     'directions',
//                     'rooms.room_type',
//                     'ratings',
//                     'detailed_attributes',
//                     'medias',
//                     'order'
//                 ])
//                 ->where('status','active')
//                 // ->where(function ($query) use ($request) {
//                 //     $query->whereHas('order',function ($q) use ($request){
//                 //       $q->whereHasMorph('orderable',
//                 //           ['App\Models\Property'], // تحدید المودیلات المسموحة
//                 //           function ($query) {
//                 //               $query->where('status', 'posted');
//                 //           });
//                 //     });
//                 // })

//                 ->when(isset(request()->publication_type), function ($q) use ($request) {
//                     $q->where('publication_type', request()->publication_type);
//                 })
//                 ->when(isset(request()->main_category_id), function ($q) use ($request) {
//                     $q->whereHas('sub_category', function ($q) use ($request) {
//                         $q->where('main_category_id', request()->main_category_id);
//                     });
//                 })
//                 ->when(isset(request()->sub_category_id), function ($q) use ($request) {
//                     $q->where('category_id', request()->sub_category_id);
//                 })
//                 ->when(isset(request()->min_area), function ($q) use ($request) {
//                     $q->where('area', '>=', request()->min_area);
//                 })
//                 ->when(isset(request()->max_area), function ($q) use ($request) {
//                     $q->where('area', '<=', request()->max_area);
//                 })
//                 ->when(isset(request()->ownership_type_id), function ($q) use ($request) {
//                     $q->where('ownership_type_id', request()->ownership_type_id);
//                 })
//                 ->when(isset(request()->min_price), function ($q) use ($request) {
//                     $q->where('price_history_price', '>=', request()->min_price);
//                 })
//                 ->when(isset(request()->max_price), function ($q) use ($request) {
//                     $q->where('price_history_price', '<=', request()->max_price);
//                 })
//                 ->when(isset(request()->country_id), function ($q) use ($request) {
//                     $q->whereHas('region.city', function ($q) use ($request) {
//                         $q->where('country_id', request()->country_id);
//                     });
//                 })
//                 ->when(isset(request()->city_id), function ($q) use ($request) {
//                     $q->whereHas('region', function ($q) use ($request) {
//                         $q->where('city_id', request()->city_id);
//                     });
//                 })
//                 ->when(isset(request()->region_id), function ($q) use ($request) {
//                     $q->where('region_id', request()->region_id);
//                 })
//                 ->when(isset(request()->pledge_type_id), function ($q) use ($request) {
//                     $q->where('pledge_type_id', request()->pledge_type_id);
//                 })
//                 ->when(isset(request()->min_age), function ($q) use ($request) {
//                     $q->where('age', '>=', request()->min_age);
//                 })
//                 ->when(isset(request()->max_age), function ($q) use ($request) {
//                     $q->where('age', '<=', request()->max_age);
//                 })
//                 ->when(isset(request()->secondary_address), function ($q) use ($request) {
//                     $q->whereRaw("MATCH(secondary_address) AGAINST(? IN BOOLEAN MODE)", [request()->secondary_address]);
//                 })
//                 ->when(isset(request()->features) && !empty(request()->features), function ($q) use ($request) {
//                     $q->whereHas('features', function ($q) use ($request) {
//                         $q->whereIn('feature_id', request()->features);
//                     });
//                 })
//                 ->when(isset(request()->directions) && !empty(request()->directions), function ($q) use ($request) {
//                     $q->whereHas('directions', function ($q) use ($request) {
//                         $q->whereIn('directions.id', request()->directions);
//                     });
//                 })
//                 ->when(isset(request()->rent_type) && request()->rent_type, function ($q) use ($request) {
//                     $q->where('rent_price_type', request()->rent_type);
//                 })
//                 ->when(isset(request()->detailed_attributes)&& !empty(request()->detailed_attributes), function ($q) use ($request) {
//                     $q->whereHas('detailed_attributes', function ($q) use ($request) {
//                         $q->where(function ($sub) use ($request) {
//                             foreach (request()->detailed_attributes as $key => $value) {
//                                 $sub->orWhere([
//                                     'key' => $key,
//                                     'value' => $value
//                                 ]);
//                             }
//                         });
//                     });
//                 })
//                 ->when(isset(request()->rooms)&& !empty(request()->rooms), function ($q) use ($request) {
//                     $q->whereHas('rooms', function ($q) use ($request) {
//                         $q->where(function ($sub) use ($request) {
//                             foreach (request()->rooms as $room) {
//                                 $sub->orWhere([
//                                     'room_type_id' => $room['type_id'],
//                                     'count' => $room['number']
//                                 ]);
//                             }
//                         });
//                     });
//                 })
//                 // ->whereHas('order',function ($q) use ($request){
//                 //       $q->whereHasMorph('orderable',
//                 //           ['App\Models\Property'], // تحدید المودیلات المسموحة
//                 //           function ($query) {
//                 //               $query->where('status', 'posted');
//                 //           });
//                 //     })

//                 ->whereExists(function ($query) {
//     $query->select(DB::raw(1))
//           ->from('advertisements')
//           ->whereColumn('advertisements.advertisementable_id', 'properties.id')
//           ->where('advertisements.advertisementable_type', Property::class);
// })
// ->whereHas('order.user.roles', function ($query) {
//         $query->where('roles.title', 'merchant'); // استخدام title بدلاً من name
//     })
//     ->with(['order.user.roles' => function ($query) {
//         $query->select('roles.id', 'roles.title as name'); // استخدام alias
//     }])
//                 ->get();
//                 // ->paginate(25);
//             return $this->returnData('advertising',PropertyResource::collection($properties));
//         }catch (\Exception $ex) {
//             return $this->returnError($ex->getCode(), $ex->getMessage());
//         }
//     }

    public function search_properties_by_cityId($request)
    {
        // TODO: Implement search_location_text_properties() method.
        try {
            $city_id=request()->city_id;
            $properties=Property::whereHas('region',function($q) use ($city_id){
                $q->where('city_id',$city_id);
            })
                ->with([
                    'advertisement',
                    'ratings',
                    'directions',
                    'region',
                    'pledge_type',
                    'ownership_type',
                    'rooms',
                    'features',
                    'detailed_attributes',
                    'medias'
                ])
                ->orderByDesc('updated_at')
                ->get();
            return $this->returnData('advertisements',PropertyResource::collection($properties));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function get_all_directions()
    {
        // TODO: Implement get_all_directions() method.
        try {
            $all=Direction::with('translation')->get();
            return $this->returnData('directions',IdTitleResource::collection($all));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function get_all_rooms_types()
    {
        // TODO: Implement get_all_rooms_types() method.
        try {
            $all=RoomType::with('translation')->get();
            return $this->returnData('rooms_types',IdNameResource::collection($all));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function get_all_main_categories()
    {
        // TODO: Implement get_all_main_categories() method.
        try {
            $all=PropertyMainCategory::with('translation')->get();
            return $this->returnData('main_categories',IdNameResource::collection($all));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function get_all_sub_categories_by_id_main_category($id)
    {
        // TODO: Implement get_all_sub_categories_by_id_main_category() method.
        try {
            $all=PropertySubCategory::where('main_category_id',$id)->with('translation')->get();
            return $this->returnData('sub_categories',IdNameResource::collection($all));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function get_all_categories()
    {
        // TODO: Implement get_all_categories() method.
        try {
            $all=PropertyMainCategory::with(['sub_categories','translation'])->get();
            return $this->returnData('all_categories',PropertyCategorisResource::collection($all));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function add_property_photos($request)
    {
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $property_id=request()->property_id;
            $property=Property::where('id',$property_id)->first();
            $exists=$this->relationship_exists($user->id,$property->order->id,'orders',User::class);
            if ($exists){
                $medias = $request->file('medias');
                foreach ($medias as $media) {
                    $type = $media->getClientOriginalExtension();
                    $size = $media->getSize();
                    $url=$this->UploadeImage('properties_media',$media);
                    $property->medias()->create([
                        'url'=>$url,
                        'type'=>$type,
                        'size'=>$size
                    ]);
                }
                DB::commit();
                return $this->returnSuccessMessage('Add Successfully');
            }
            DB::rollBack();
            return $this->returnError(404,'Working Error');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_property_photos($request)
    {
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $property_id=request()->property_id;
            $property=Property::where('id',$property_id)->first();
            $exists=$this->relationship_exists($user->id,$property->order->id,'orders',User::class);
            if ($exists){
                $media_id=request()->media_id;
                $media=Media::where('id',$media_id)->first();
                $url=$media->url;
                if (Str::startsWith($url,'/'))
                {
                    File::delete(public_path($this->after('/',$url)));
                }else
                {
                    File::delete(public_path($url));
                }
                $media->delete();
                DB::commit();
                return $this->returnSuccessMessage('Delete Successfully');
            }
            DB::rollBack();
            return $this->returnError(404,'Working Error');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

        public function get_all_ownership_types()
    {
        // TODO: Implement get_all_ownership_types() method.
        try {
            $ownership_types=OwnershipType::with('translation')->get();
            return $this->returnData('ownership_types',IdNameResource::collection($ownership_types),'');
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

        public function get_all_pledge_types()
    {
        // TODO: Implement get_all_pledge_types() method.
        try {
            $pledge_types=PledgeType::with('translation')->get();
            return $this->returnData('pledge_types',IdNameResource::collection($pledge_types),'');
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function search_properties_by_serialNumber($request)
    {
        // TODO: Implement search_properties_by_serialNumber() method.
        try {
            $serial_number=$request->serial_number;
            $all=AdvertisementResource::collection(Advertisement::with(['advertisementable','subscribe.user'])
                ->where('active',true)
                ->whereHasMorph(
                    'advertisementable',
                    ['App\Models\Property'], // تحدید المودیلات المسموحة
                    function ($query) use ($serial_number) {
                        $query->where('serial_number','LIKE',"%{$serial_number}%");
                    }
                )
                ->orderByDesc('updated_at')
                ->get());
            return $this->returnData('advertising',$all);
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
