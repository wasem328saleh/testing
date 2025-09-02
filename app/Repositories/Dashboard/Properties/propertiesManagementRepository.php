<?php

namespace App\Repositories\Dashboard\Properties;

use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\ConfigAttributesResource;
use App\Http\Resources\IdNameResource;
use App\Http\Resources\IdTitleResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PropertyCategorisResource;
use App\Jobs\SendNotification;
use App\Models\Advertisement;
use App\Models\Classification;
use App\Models\ConfigAttribute;
use App\Models\Direction;
use App\Models\Order;
use App\Models\OwnershipType;
use App\Models\PledgeType;
use App\Models\Property;
use App\Models\PropertyMainCategory;
use App\Models\PropertySubCategory;
use App\Models\Role;
use App\Models\RoomType;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class propertiesManagementRepository implements PropertiesManagementRepositoryInterface
{
    //dl(s1gu'YQoP;F^-zazwas000z
    //u716440055_real_estate_db
    //u716440055_abdulrahmanzaz
use GeneralTrait;
    public function get_all_properties()
    {
        // TODO: Implement get_all_properties() method.
        $all=AdvertisementResource::collection(Advertisement::with(['advertisementable','subscribe.user'])
            ->where('active',true)
            ->orderByDesc('created_at')
            ->get());
        return $all;
    }
    public function get_all_orders()
    {
        // TODO: Implement get_all_order() method.
        try {
            return OrderResource::collection(Order::where('classification_id',Classification::where('name','properties')->first()->id)
                ->where('status',"pending")
                ->with('orderable')->get());
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
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
            $user=Role::where('title','super_admin')->first()->users()->first();
            if (!$user){
                DB::rollBack();
                return $this->returnError(500,'working error');
            }
            $classification_id=request()->classification_id;
            $existingAttributes  = $this->config_attributes(intval($classification_id),intval(request()->sub_category_id??request()->sub_category_id));
            $attributes_name=[];
            foreach ($existingAttributes as $attribute){
                $attributes_name[]=$attribute['attribute_name'];
            }
            $serial_number=$this->generate_serial_number(Property::class,$classification_id);
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
            $property->status='active';
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
                'advertisement_end_date'=>Carbon::maxValue()->toDate(),
                'classification_id'=>$classification_id,
                'active'=>true
            ]);
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,$classification_id),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$user->id,
                'classification_id'=>$classification_id,
                'status'=>'posted'
            ]);
            DB::commit();
            return $this->returnSuccessMessage('advertising added successfully');
        }catch (\Exception $exception)
        {
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function update_property($request)
    {
        // TODO: Implement update_property() method.
        try {
            DB::beginTransaction();
            $property_id=request()->property_id;
            $property=Property::where('id',$property_id)->first();
            if (!$property){
                DB::rollBack();
                return $this->returnError(404,'property not found');
            }
            $price=0;
            $priceHistory = $property->price_history;

            $publication_type=request()->publication_type?request()->publication_type:$property->publication_type;
            $property->publication_type=$publication_type;
//            return
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
                $sub_category_id=$property->category_id;
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
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }


//     public function update_property($request)
//     {
//         // TODO: Implement update_property() method.
//         try {
//             DB::beginTransaction();
//             $property_id=request()->property_id;
//             $property=Property::where('id',$property_id)->first();
//             if (!$property){
//                 DB::rollBack();
//                 return $this->returnError(404,'property not found');
//             }
//             $price=0;
//             $priceHistory = $property->price_history;

//             $publication_type=request()->publication_type?request()->publication_type:$property->publication_type;
//             $property->publication_type=$publication_type;
// //            return
//             if (request()->price)
//             {
//                 $new_price=intval(request()->price);
//                 $h_pp=$property->price_history_price;
//                 $property->update(
//                     [
//                         'price_history'=>[
//                             'price'=>$new_price,
//                             'history'=>$h_pp
//                         ]
//                     ]
//                 );
//                 $price=$new_price;
//             }else{
//                 $price=$property->price_history_price;
//             }
//             if ($publication_type==='sale'){
//                 $property->sale_price=$price;
//             }elseif ($publication_type==='rent'){
//                 $rent_type='';
//                 if (request()->rent_type){
//                     $rent_type=request()->rent_type;
//                 }else{
//                     $rent_type=$property->rent_type;
//                 }
//                 $rent_price=['type'=>$rent_type,'price'=>$price];
//                 $property->rent_price=$rent_price;
//             }
//             if (request()->area !== null){
//                 $property->area=request()->area;
//             }
//             if (request()->age !== null){
//                 $property->age=request()->age;
//             }
//             if (request()->map_points !== null){
//                 $property->map_points=json_encode(request()->map_points);
//             }
//             if (request()->secondary_address !== null){
//                 $property->secondary_address=request()->secondary_address;
//             }
//             if (request()->region_id !== null){
//                 $property->region_id=request()->region_id;
//             }
//             $existingAttributes=[];
//             $classification_id=Classification::where('name','properties')->first()->id;
//             $sub_category_id=0;
//             if (request()->sub_category_id !== null){
//                 $sub_category_id=request()->sub_category_id;
//                 $existingAttributes  = $this->config_attributes(intval($classification_id),intval(request()->sub_category_id));
//                 $property->sub_category_id=request()->sub_category_id;
//             }else{
//                 $sub_category_id=$property->sub_category_id;
//                 $existingAttributes  = $this->config_attributes(intval($classification_id),intval($property->sub_category_id));
//             }
//             $attributes_name=[];
//             foreach ($existingAttributes as $attribute){
//                 $attributes_name[]=$attribute['attribute_name'];
//             }
//             if (request()->pledge_type_id !== null&&in_array("pledge_type_id",$attributes_name)){
//                 $property->pledge_type_id=request()->pledge_type_id;
//             }
//             $property->save();

//             if ($request->rooms !== null&&in_array("rooms",$attributes_name)){
//                 $rooms=$request->rooms;
//                 foreach ($rooms as $room) {
//                     $id=$room['id'];
//                     $room_number=$room['number'];
//                     $property->rooms()->where('id',$id)->update([
//                         'count'=>$room_number
//                     ]);
//                 }
//             }
//             if ($request->directions !== null){
//                 $directions=$request->directions;
//                 $property->directions()->sync($directions);
//             }
//             if ($request->features !== null){
//                 $property->features()->delete();
//                 $features=$request->features;
//                 foreach ($features as $feature) {
//                     $property->features()->create([
//                         'feature_id'=>$feature
//                     ]);
//                 }
//             }
//             $this->edit_detailed_attributes($request,$property,$sub_category_id);
//             DB::commit();
//             return $this->returnSuccessMessage('Updated Property Successfully');
//         }catch (\Exception $exception){
//             DB::rollBack();
//             return $this->returnError($exception->getCode(),$exception->getMessage());
//         }
//     }

    public function delete_property($request)
    {
        // TODO: Implement delete_property() method.
        try {
            DB::beginTransaction();
            $property_id=request()->property_id;
            $property=Property::where('id',$property_id)->first();
            if (!$property){
                DB::rollBack();
                return $this->returnError(404,'property not found');
            }
            $property->advertisement()->delete();
            $property->order()->delete();
            $property->medias()->delete();
            $property->detailed_attributes()->delete();
            $property->favorites()->delete();
            $property->ratings()->delete();
            $property->features()->delete();

            $property->delete();

            DB::commit();
            return $this->returnSuccessMessage('Deleted Property Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function change_property_status($action,$request)
    {
        // TODO: Implement change_property_status() method.
        try {
            DB::beginTransaction();
            if ($action==="accept_order"){
                $property_id=$request->property_id;
                $property=Property::where('id',$property_id)->first();
                if ($property->status=="inactive"){
                    $property->status="active";
                    $property->save();
                    $advertisement=$property->advertisement;
                    $advertisement->active=true;
                    $advertisement->save();
                    $order=$property->order;
                    $order->status="posted";
                    $order->save();

                    //send notifications
                    $title = trans('notifications.accept_order');
                    $body =trans('notifications.accept_order_body') . " : " . $property->serial_number." ".trans('notifications.accept_order_body1');
                    $type = 'success';
                    $user=$property->order->user;

                        $data=[
                            'users'=>[$user],
                            'title'=>$title,
                            'body'=>$body,
                            'type'=>$type
                        ];
                        dispatch(new SendNotification($data));
                }else{
                    DB::rollBack();
                    return $this->returnError(500,"This property is Already Active");
                }
                DB::commit();
                return $this->returnSuccessMessage("Accepted");
            }
            if ($action==="cancel_order"){
                $property_id=$request->property_id;
                $reply=$request->reply;
                $property=Property::where('id',$property_id)->first();
                $property->status="inactive";
                $property->save();
                $order=$property->order;
                if ($order->status=="posted"||$order->status=="pending"){
                    $order->status="cancelled";
                    $order->reply=$reply;
                    $order->save();

                    //send notifications
                    $title = trans('notifications.cancel_order');
                    $body =trans('notifications.cancel_order_body1') . " : " . $property->serial_number."\n".trans('notifications.cancel_order_body2')." : ".$reply;
                    $type = 'error';
                    $user=$property->order->user;
                    $data=[
                        'users'=>[$user],
                        'title'=>$title,
                        'body'=>$body,
                        'type'=>$type
                    ];
                    dispatch(new SendNotification($data));

                }else{
                    DB::rollBack();
                    return $this->returnError(500,"This property is Already Inactive");
                }
                DB::commit();
                return $this->returnSuccessMessage("Cancelled");
            }
            return  $this->returnError(404,"Action Not Found");
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_all_archived_properties()
    {
        // TODO: Implement get_all_archived_properties() method.
    }

    public function remove_from_archived($request)
    {
        // TODO: Implement remove_from_archived() method.
    }

    public function unarchived_from_archived($request)
    {
        // TODO: Implement unarchived_from_archived() method.
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

    public function add_room_type($request)
    {
        // TODO: Implement add_room_type() method.
        try {
            $name=request()->name;
            RoomType::create([
                'name'=>$name,
            ]);
            return $this->returnSuccessMessage('Room Type Added Successfully');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_room_type($request)
    {
        // TODO: Implement update_room_type() method.
        try {
            DB::beginTransaction();
            $room_type_id=request()->room_type_id;
            $room_type=RoomType::findOrFail($room_type_id);
            if (!$room_type){
                DB::rollBack();
                return $this->returnError(404,'room_type not found');
            }
            if (request()->has('name')){
                $room_type->name=request()->name;
                $room_type->save();
            }
            DB::commit();
            return $this->returnSuccessMessage('Room Type Updated Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_room_type($request)
    {
        // TODO: Implement delete_room_type() method.
        try {
            DB::beginTransaction();
            $room_type_id=request()->room_type_id;
            $room_type=RoomType::findOrFail($room_type_id);
            if (!$room_type){
                DB::rollBack();
                return $this->returnError(404,'room_type not found');
            }
            $room_type->delete();
            DB::commit();
            return $this->returnSuccessMessage('Room Type Deleted Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
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

    public function add_main_category($request)
    {
        // TODO: Implement add_main_category() method.
        try {
            $name=request()->name;
            PropertyMainCategory::create([
                'name'=>$name
            ]);
            return $this->returnSuccessMessage('Property Main Category Added Successfully');
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function update_main_category($request)
    {
        // TODO: Implement update_main_category() method.
        try {
            DB::beginTransaction();
            $main_category_id=request()->main_category_id;
            $main_category=PropertyMainCategory::findOrFail($main_category_id);
            if (!$main_category){
                DB::rollBack();
                return $this->returnError(404,'main_category not found');
            }
            if (request()->has('name')){
                $main_category->name=request()->name;
                $main_category->save();
            }
            DB::commit();
            return $this->returnSuccessMessage('Property Main Category Updated Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_main_category($request)
    {
        // TODO: Implement delete_main_category() method.
        try {
            DB::beginTransaction();
            $main_category_id=request()->main_category_id;
            $main_category=PropertyMainCategory::findOrFail($main_category_id);
            if (!$main_category){
                DB::rollBack();
                return $this->returnError(404,'main_category not found');
            }
            $main_category->delete();
            DB::commit();
            return $this->returnSuccessMessage('Property Main Category Deleted Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
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

    public function add_sub_category($request)
    {
        // TODO: Implement add_sub_category() method.
        try {
            DB::beginTransaction();
            $main_category_id=request()->main_category_id;
            $main_category=PropertyMainCategory::findOrFail($main_category_id);
            if (!$main_category){
                DB::rollBack();
                return $this->returnError(404,'main_category not found');
            }
            $name=request()->name;
            $main_category->sub_categories()->create([
                'name'=>$name,
            ]);
            DB::commit();
            return $this->returnSuccessMessage('Property Sub Category Added Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_sub_category($request)
    {
        // TODO: Implement update_sub_category() method.
        try {
            DB::beginTransaction();
            $sub_category_id=request()->sub_category_id;
            $sub_category=PropertySubCategory::findOrFail($sub_category_id);
            if (!$sub_category){
                DB::rollBack();
                return $this->returnError(404,'sub_category not found');
            }
            if (request()->has('main_category_id')){
                $main_category_id=request()->main_category_id;
                $main_category=PropertyMainCategory::findOrFail($main_category_id);
                if (!$main_category){
                    DB::rollBack();
                    return $this->returnError(404,'main_category not found');
                }
                $sub_category->main_category_id=$main_category_id;
            }
            if (request()->has('name')){
                $sub_category->name=request()->name;
            }
            $sub_category->save();
            DB::commit();
            return $this->returnSuccessMessage('Property Sub Category Updated Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_sub_category($request)
    {
        // TODO: Implement delete_sub_category() method.
        try {
            DB::beginTransaction();
            $sub_category_id=request()->sub_category_id;
            $sub_category=PropertySubCategory::findOrFail($sub_category_id);
            if (!$sub_category){
                DB::rollBack();
                return $this->returnError(404,'sub_category not found');
            }
            $sub_category->delete();
            DB::commit();
            return $this->returnSuccessMessage('Property Sub Category Deleted Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
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

    public function add_direction($request)
    {
        // TODO: Implement add_direction() method.
        try {
            $title=request()->title;
            Direction::create([
                'title'=>$title,
            ]);
            return $this->returnSuccessMessage('Direction Added Successfully');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_direction($request)
    {
        // TODO: Implement update_direction() method.
        try {
            DB::beginTransaction();
            $direction_id=request()->direction_id;
            $direction=Direction::findOrFail($direction_id);
            if (!$direction){
                DB::rollBack();
                return $this->returnError(404,'direction not found');
            }
            if (request()->has('title')){
                $direction->title=request()->title;
            }
            $direction->save();
            DB::commit();
            return $this->returnSuccessMessage('Direction Updated Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_direction($request)
    {
        // TODO: Implement delete_direction() method.
        try {
            DB::beginTransaction();
            $direction_id=request()->direction_id;
            $direction=Direction::findOrFail($direction_id);
            if (!$direction){
                DB::rollBack();
                return $this->returnError(404,'direction not found');
            }

            $direction->delete();
            DB::commit();
            return $this->returnSuccessMessage('Direction Deleted Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function edited_activation_property($request)
    {
        // TODO: Implement edited_activation_my_property() method.
        try {
            DB::beginTransaction();
            $property_id=request()->property_id;
            $property=Property::where('id',$property_id)->first();
            if (!$property){
                DB::rollBack();
                return $this->returnError(404,'property not found');
            }


                if ($property->status=="active"){
                    $property->status="inactive";
                    $property->save();
                    $advertisement=$property->advertisement;
                    $advertisement->active=false;
                    $advertisement->save();
                    $order=$property->order;
                    $order->status="cancelled";
                    $order->save();
                    DB::commit();
                    return $this->returnSuccessMessage('Inactivated Property Successfully');
                }elseif ($property->status=="inactive"){

                    $property->status="active";
                    $property->save();
                    $advertisement=$property->advertisement;
                    $advertisement->active=true;
                    $advertisement->save();
                    $order=$property->order;
                    $order->status="posted";
                    $order->save();
                    DB::commit();
                    return $this->returnSuccessMessage('Activated Property Successfully');

            }
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
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

    public function add_ownership_type($request)
    {
        // TODO: Implement add_ownership_type() method.
        try {
            DB::beginTransaction();
            $name=request()->name;
            OwnershipType::create([
                'name'=>$name
            ]);
            $localization=new GoogleTranslate();
            $localization->translate($name);
            $lang=$localization->getLastDetectedSource();
            $languages=array_keys(config('environment_system.available_languages'));
            DB::commit();
            return $this->returnSuccessMessage('Ownership Type Added Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_ownership_type($request)
    {
        // TODO: Implement update_ownership_type() method.
        try {
            DB::beginTransaction();
            $ownership_type_id=request()->ownership_type_id;
            $ownership_type=OwnershipType::findOrFail($ownership_type_id);
            if (!$ownership_type){
                DB::rollBack();
                return $this->returnError(404,'ownership type not found');
            }
            if (request()->has('name')){
                $ownership_type->name=request()->name;
            }
            $ownership_type->save();
            DB::commit();
            return $this->returnSuccessMessage('Ownership Type Updated Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_ownership_type($request)
    {
        // TODO: Implement delete_ownership_type() method.
        try {
            DB::beginTransaction();
            $ownership_type_id=request()->ownership_type_id;
            $ownership_type=OwnershipType::findOrFail($ownership_type_id);
            if (!$ownership_type){
                DB::rollBack();
                return $this->returnError(404,'ownership type not found');
            }
            $ownership_type->delete();
            DB::commit();
            return $this->returnSuccessMessage('Ownership Type Deleted Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
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

    public function add_pledge_type($request)
    {
        // TODO: Implement add_pledge_type() method.
        try {
            DB::beginTransaction();
            $name=request()->name;
            PledgeType::create([
                'name'=>$name
            ]);
            DB::commit();
            return $this->returnSuccessMessage('Pledge Type Added Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_pledge_type($request)
    {
        // TODO: Implement update_pledge_type() method.
        try {
            DB::beginTransaction();
            $pledge_type_id=request()->pledge_type_id;
            $pledge_type=PledgeType::findOrFail($pledge_type_id);
            if (!$pledge_type){
                DB::rollBack();
                return $this->returnError(404,'pledge type not found');
            }
            if (request()->has('name')){
                $pledge_type->name=request()->name;
            }
            $pledge_type->save();
            DB::commit();
            return $this->returnSuccessMessage('Pledge Type Updated Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_pledge_type($request)
    {
        // TODO: Implement delete_pledge_type() method.
        try {
            DB::beginTransaction();
            $pledge_type_id=request()->pledge_type_id;
            $pledge_type=PledgeType::findOrFail($pledge_type_id);
            if (!$pledge_type){
                DB::rollBack();
                return $this->returnError(404,'pledge type not found');
            }
            $pledge_type->delete();
            DB::commit();
            return $this->returnSuccessMessage('Pledge Type Deleted Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
