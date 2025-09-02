<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use App\Http\Resources\AddressLocationResource;
use App\Http\Resources\AddressResource;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\ConfigAttributesResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\IdNameResource;
use App\Http\Resources\IdTitleResource;
use App\Http\Resources\MyFavoriteResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PropertyCategorisResource;
use App\Http\Resources\PropertyResource;
use App\Http\Resources\ProviderResource;
use App\Http\Resources\UrlResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Models\Advertisement;
use App\Models\AdvertisingPackage;
use App\Models\CategoryService;
use App\Models\City;
use App\Models\Classification;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\ConfigAttribute;
use App\Models\Country;
use App\Models\Direction;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OwnershipType;
use App\Models\Property;
use App\Models\PropertyMainCategory;
use App\Models\PropertySubCategory;
use App\Models\Region;
use App\Models\Role;
use App\Models\RoomType;
use App\Models\ServiceProvider;
use App\Models\Subscribe;
use App\Models\User;
use App\Rules\RelationshipExists;
use App\Traits\ApiResponderTrait;
use App\Traits\GeneralTrait;
//use Carbon\Carbon;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;


use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Client as HttpClient;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
//    use ApiResponderTrait;
    use GeneralTrait;


    public function testauth()
    {
        try {
            $ownership_types=OwnershipType::with('translation')->get();
            return $this->returnData('ownership_types',IdNameResource::collection($ownership_types),'');
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }
    public function noti()
    {
        $user = User::where('id', 2)->first();
        $user->notifications()->create([
            'title'=>'test title',
            'body'=>'test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body',
            'type'=>'success',
            'is_read'=>true
        ]);
        $user->notifications()->create([
            'title'=>'test title',
            'body'=>'test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body',
            'type'=>'error',
            'is_read'=>true
        ]);
        $user->notifications()->create([
            'title'=>'test title',
            'body'=>'test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body',
            'type'=>'warning',
            'is_read'=>true
        ]);
        $user->notifications()->create([
            'title'=>'test title',
            'body'=>'test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body',
            'type'=>'info',
            'is_read'=>true
        ]);

        $user->notifications()->create([
            'title'=>'test title',
            'body'=>'test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body',
            'type'=>'success',
            'is_read'=>false
        ]);
        $user->notifications()->create([
            'title'=>'test title',
            'body'=>'test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body',
            'type'=>'error',
            'is_read'=>false
        ]);
        $user->notifications()->create([
            'title'=>'test title',
            'body'=>'test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body',
            'type'=>'warning',
            'is_read'=>false
        ]);
        $user->notifications()->create([
            'title'=>'test title',
            'body'=>'test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body test body',
            'type'=>'info',
            'is_read'=>false
        ]);
        return "Doneee";
    }
    public function tt()
    {
//        $this->generate_session_services();
//        return "Done";
        $user = User::find(1);

        User::factory()->count(1)->create();
//        $user = User::where('id', 2)->first();
//
////        $user=Auth::user();
//        $all=OrderResource::collection($user->orders()->with('orderable')->get());
//        return $this->returnData('my_advertising',$all);

//        $a=$user->orders()
//            ->where('for_service_provider',false)
//            ->where('status','posted')
//            ->whereHasMorph(
//                'orderable',
//                ['App\Models\Property'], // تحدید المودیلات المسموحة
//                function ($query) {
//                    $query->where('status', 'active')
//                        ->whereHas('advertisement', function ($query) {
//                            $query->where('active', true);
//                        });
//                }
//            )
//            ->with('orderable.advertisement.advertisementable')->get()
////        )
//            ->orderable->advertisement->advertisementable;
//        $a->update(
//            [
//                'price_history'=>[
//                    'price'=>$a->price_history_price,
//                    'history'=>222222
//                ]
//            ]
//        );
//        return $a;
//        return
//            new AdvertisementResource($user->orders()
//                ->where('for_service_provider',false)
//                ->where('status','posted')
//                ->whereHasMorph(
//                    'orderable',
//                    ['App\Models\Property'], // تحدید المودیلات المسموحة
//                    function ($query) {
//                        $query->where('status', 'active')
//                            ->whereHas('advertisement', function ($query) {
//                                $query->where('active', true);
//                            });
//                    }
//                )
//                ->with('orderable.advertisement.advertisementable')->first()
////        )
//                ->orderable->advertisement)
//           ;
//            ->pluck('orderable.advertisement');
        $ss = AdvertisementResource::collection(collect($user->orders()
            ->where('for_service_provider',false)
            ->where('status','posted')
            ->whereHasMorph(
                'orderable',
                ['App\Models\Property'], // تحدید المودیلات المسموحة
                function ($query) {
                    $query->where('status', 'active')
                        ->whereHas('advertisement', function ($query) {
                            $query->where('active', true);
                        });
                }
            )
            ->with('orderable.advertisement.advertisementable')->get())->pluck('orderable.advertisement'));

        return $ss;
//        return collect($user->orders_ad()->get())->toArray();
//        return $user->advertising()
    }
    public function t()
    {

        $admin_id = Role::where('title', 'super_admin')
            ->with('users')
            ->first()
            ->users()
            ->first()
            ->id;

        $orders = Order::with([
            'orderable',
            'user.personal_identification_papers',
            'user.region'
        ])
            ->where('classification_id', '!=', null)
            ->where('user_id', '!=', $admin_id)
            ->whereIn('status', ['pending', 'posted','cancelled'])
            ->paginate(10); // تحديد عدد العناصر لكل صفحة
return $this->returnData('orders',new OrderCollection($orders));
//        return response()->json([
//            'status' => true,
//            'errNum' => "S000",
//            'msg' => "this is all orders",
//            'orders' => $orders
//        ]);
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => "this is all orders",
            'orders' => new OrderCollection($orders)
        ]);

//        $admin_id=Role::where('title','super_admin')->with('users')->first()->users()->first()->id;
//        $orders=OrderResource::collection(Order::with(['orderable','user.personal_identification_papers'])
//
//            ->get());
//
//        return response()->json([
//            'status' => true,
//            'errNum' => "S000",
//            'msg' => "this is all orders",
//            'orders' => $orders
//        ]);


$complaints=Complaint::all();
        $complaints_c=ComplaintResource::collection($complaints);
        return $complaints_c;
//        return RoomType::whereIn('name', ['مطبخ', 'حمام'])
//            ->pluck('id');
        try {
            DB::beginTransaction();
//            $all=AdvertisementResource::collection(Advertisement::with(['advertisementable','subscribe.user'])
//                ->where('active',true)
//                ->whereHas('advertisementable',function ($query){
//                    $query->where('publication_type','sale');
//                })
//                ->get());
//            $a=new AdvertisementResource(Advertisement::with(['advertisementable','subscribe.user'])
//                ->where('active',true)
//                ->whereHas('advertisementable',function ($query){
//                    $query->where('publication_type','sale');
//                })
//                ->first());
//            DB::commit();
//            return $this->returnData('advertising',$a);

            DB::commit();

        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }

    }
    public function test_rule(Request $request)
    {
        //Str::lower(Auth::user()->roles()->first()->title)===Str::lower('User')
//        $a1=Str::lower(Auth::user()->roles()->first()->title);
//        $a2=Str::lower('User');
//        $property=Property::where('id',1)->first();
//        $order_status=$property->order->status;
//        return $order_status;
//        return (Str::lower(Auth::user()->roles()->first()->title)===Str::lower('User'))*8;


//        return UserResource::collection(User::all());
//        $user = Auth::user()->token();
//        return $user->user;
//          return Role::where('title','Admin')->with('users')->first()->users()->first()->id;
        $user_id=request()->user_id;
//        return request()->user_id;
        $user=User::where('id',$user_id)->first();
            return $this->returnData('user_profile',new UserProfileResource($user));
//        if ($user)
//        {
//            return $this->returnData('user_profile',new UserProfileResource($user));
//        }
    }
    public function test_request(TestRequest $request)
    {

//        $a=[            [
//            'attribute_name'=>'rooms',
//            'rules'=>['array'],
//            'is_required'=>true,
//            'is_used_for_filtering'=>false,
//            'classification_id'=>1
//        ],
//            [
//                'attribute_name'=>'rooms.*.type_id',
//                'rules'=>['required', 'numeric','exists:room_types,id'],
//                'is_required'=>true,
//                'is_used_for_filtering'=>false,
//                'classification_id'=>1
//            ],
//            [
//                'attribute_name'=>'rooms.*.number',
//                'rules'=>['required', 'numeric'],
//                'is_required'=>true,
//                'is_used_for_filtering'=>false,
//                'classification_id'=>1
//            ],];
//        foreach ($a as $attribute)
//        {
//            ConfigAttribute::create([
//                'attribute_name'=>$attribute['attribute_name'],
//                'rules'=>json_encode($attribute['rules']),
//                'is_required'=>$attribute['is_required'],
//                'is_used_for_filtering'=>$attribute['is_used_for_filtering'],
//                'classification_id'=>$attribute['classification_id'],
//                'category_id'=>array_key_exists('category_id',$attribute)?$attribute['category_id']:null,
//            ]);
//        }
//        return $request->toArray();
//       $category_id=1;
        $attributes=collect(ConfigAttributesResource::collection(ConfigAttribute::select('attribute_name')
            ->where('category_id',request()->sub_category_id)
            ->where('is_required',true)
            ->get()))
            ->pluck('attribute_name')
            ->toArray();
        $s=[];
        foreach ($attributes as $attribute) {
            if ($request->$attribute !=null&&$attribute!='pledge_type_id'&&$attribute!='rooms'){
                $s[]=['key'=>$attribute,'value'=>$request->$attribute];
            }
//            $property->detailed_attributes()->create([
//                'key'=>Str::replace('_',' ',$attribute),
//                'value'=>request()->$attribute,
//            ]);
        }
        return $s;
//        return $request->toArray();
//        $detailed_attributes=[];
//
//
//        if (request()->level_location !== null){
//            $level_location=request()->level_location;
//            $detailed_attributes[]=['key'=>'level_location','value'=>$level_location];
//        }
//
//        if (request()->levels_count !== null){
//            $levels_count=request()->levels_count;
//            $detailed_attributes[]=['key'=>'levels_count','value'=>$levels_count];
//        }
//
//        if (request()->water_source !== null){
//            $water_source=request()->water_source;
//            $detailed_attributes[]=['key'=>'water_source','value'=>$water_source];
//        }
//
//        if (request()->permissible_construction_ratio !== null){
//            $permissible_construction_ratio=request()->permissible_construction_ratio;
//            $detailed_attributes[]=['key'=>'permissible_construction_ratio','value'=>$permissible_construction_ratio];
//        }
//
//        if (request()->number_of_floors_that_can_be_built !== null){
//            $number_of_floors_that_can_be_built=request()->number_of_floors_that_can_be_built;
//            $detailed_attributes[]=['key'=>'number_of_floors_that_can_be_built','value'=>$number_of_floors_that_can_be_built];
//        }
//
//        if (request()->soil_type !== null){
//            $soil_type=request()->soil_type;
//            $detailed_attributes[]=['key'=>'soil_type','value'=>$soil_type];
//        }
//
//        if (request()->ceiling_height !== null){
//            $ceiling_height=request()->ceiling_height;
//            $detailed_attributes[]=['key'=>'ceiling_height','value'=>$ceiling_height];
//        }
//
//        if (request()->number_of_shipping_doors !== null){
//            $number_of_shipping_doors=request()->number_of_shipping_doors;
//            $detailed_attributes[]=['key'=>'number_of_shipping_doors','value'=>$number_of_shipping_doors];
//        }
//
//        if (request()->number_of_entries !== null){
//            $number_of_entries=request()->number_of_entries;
//            $detailed_attributes[]=['key'=>'number_of_entries','value'=>$number_of_entries];
//        }
//
//        if (request()->number_of_exits !== null){
//            $number_of_exits=request()->number_of_exits;
//            $detailed_attributes[]=['key'=>'number_of_exits','value'=>$number_of_exits];
//        }
//
//
//        $r=[];
//        foreach ($detailed_attributes as $value){
//            $r[]=Str::replace('_',' ',$value['key']);
//        }
//
//        return $r;
    }
    public function tester()
    {
//        $user=User::findOrFail(1)/*->with(['roles','permissions','region'])->first()*/;
//        return new UserResource($user->load(['region']));


//        return Auth::check()*8;


//        $packages=AdvertisingPackage::where('user_type','merchant')->get();
////        $packages=AdvertisingPackage::all();
//        return $this->returnData('packages',PackageResource::collection($packages),'');


//        if (Auth::check()) {
//            $user = Auth::user();
//            $user_type=$user->roles->first()->title;
//            $packages=AdvertisingPackage::whereIn('user_type',[Str::lower($user_type),'both'])->get();
//            return $this->returnData('packagesss',PackageResource::collection($packages),'');
//        } else {
//            $packages=AdvertisingPackage::where('user_type','both')->get();
//            return $this->returnData('packages',PackageResource::collection($packages),'');
//        }



//        $classification_id=1;
//        $classification_name=Classification::findOrFail($classification_id)->name;
//        return $classification_name;


//        [
//  {
//      "property_name": [
//      "required",
//      "string"
//  ]
//  },
//  {
//      "property_price": [
//      "required",
//      "numeric"
//  ]
//  }
//]

//        {
//            "property_name": [
//            "required",
//            "string"
//        ],
//  "property_price": [
//            "required",
//            "numeric"
//        ]
//}
//
//        $attributes = $this->config_attributes(1);
//         return $attributes;
//        // تحويل المصفوفة إلى تنسيق قواعد لارافيل
//        $rules = [];
//        foreach ($attributes as $attribute) {
//            $rules[$attribute['attribute_name']]= $attribute['rules'];
//        }
//
//        return collect(array_merge([
//            'classification_id'=>['required'],
//        ],$rules))->toArray();
//
//        $existingAttributes  = $this->config_attributes(1);
//        $names=[];
//        foreach ($existingAttributes as $attribute){
//            $names[]=$attribute['attribute_name'];
//        }
//        return $names;
//        $rules=$this->switch_rules_classifications(1);
//
//        // Extract allowed attribute names
//        $allowedAttributes = array_map(function ($attribute) {
//            return $attribute['attribute_name'];
//        }, $existingAttributes);
//
//// Filter the rules array
//        $filteredRules = array_intersect_key($rules, array_flip($allowedAttributes));
//
//        return array_merge([
//            'classification_id'=>['required'],
//        ],$filteredRules);
//
//        $rules=[
//            'property_name'=>['required','string'],
//            'property_price'=>['required','numeric'],
//            'car_type'=>['required','string'],
//            'car_price'=>['required','numeric'],
//        ];
//
//        $attributes_name=[
//            'property_name',
//            'car_type'
//        ];


//        $s1 = Subscribe::where('id', 1)->first();
//        $s2 = Subscribe::where('id', 2)->first();
//        $s3 = Subscribe::where('id', 3)->first();
//
//        $s1->used_advertisements_count+=8;
//        $s1->save();
//        $s3->used_advertisements_count+=8;
//        $s3->save();




//        $c1=$s1->created_at;
//        $c2=$s2->created_at;
//        $c3=$s3->created_at;
//        $s1->created_at=Carbon::parse($c1)->addDays(1);
//        $s1->save();
//        $s2->created_at=Carbon::parse($c2)->addDays(2);
//        $s2->save();
//        $s3->created_at=Carbon::parse($c3)->addDays(3);
//        $s3->save();

//          return Subscribe::all();
//        return Subscribe::orderBy('created_at','asc')->get();
//        return Subscribe::whereColumn('used_advertisements_count','<','advertisements_count')->get();

//        $a=[
//            [
//                'attribute_name'=>'description',
//                'rules'=>['required','string'],
//                'is_required'=>true,
//                'is_used_for_filtering'=>false,
//                'classification_id'=>1,
//                'category_id'=>1,
//            ],
//            [
//                'attribute_name'=>'description',
//                'rules'=>['required','string'],
//                'is_required'=>true,
//                'is_used_for_filtering'=>false,
//                'classification_id'=>1
//            ],
//        ];
//
//        return array_key_exists('category_id',$a[1])?$a[0]['category_id']:"wasem";

//        return DB::table('direction_property')->get();
//        $ssc=count(collect(PackageResource::collection(AdvertisingPackage::all()))->pluck('number_of_advertisements')->toArray());
//        $ss=array_sum(collect(PackageResource::collection(AdvertisingPackage::all()))->pluck('number_of_advertisements')->toArray());
//        return round($ss/$ssc,1);
//        return Property::where('id',2)
//            ->with(['rooms.room_type',
//                'directions',
//                'ownership_papers',
//                'features.feature',
//                'ratings',
//                'detailed_attributes',
//                'medias'
//                ])
//            ->first();
//        return "w";
//        $property_id=2;
//        $property=Property::where('id',$property_id)->first();
//        $exists=$this->relationship_exists(2,$property->order->id,'orders',User::class);
//         return $exists*8;
//        $attributes=collect(ConfigAttributesResource::collection(
//            ConfigAttribute::select('attribute_name')
//                ->where('classification_id',1)
//                ->whereNull('category_id')
//                ->get()))
//            ->pluck('attribute_name')
//            ->toArray();
//        $c=1;
//        $w=collect(ConfigAttributesResource::collection(
//            ConfigAttribute::select('attribute_name')
//                ->where('category_id',$c)
//                ->get()))
//            ->pluck('attribute_name')
//            ->toArray();
////        return $attributes;
//        $attributes=array_filter(array_merge($attributes,$w),function($v){
//            return !Str::contains($v,'.*.');
//        });
//        return $w;
////        return ConfigAttributesResource::collection(ConfigAttribute::all());

//        return $this->get_all_rules();
//        $category_id=$id;
////        $attributes=collect(ConfigAttributesResource::collection(ConfigAttribute::select('attribute_name')
////            ->where('category_id',$category_id)
////            ->where('is_required',true)
////            ->get()))->pluck('attribute_name')->toArray();
////        return $attributes;
//        return collect(ConfigAttributesResource::collection(ConfigAttribute::where('category_id',$category_id)->where('classification_id',1)->where('is_required',true)->get()))->toArray();
//        $existingAttributes  = $this->config_attributes(1,4);
//        $attributes_name=[];
//        foreach ($existingAttributes as $attribute){
//            $attributes_name[]=$attribute['attribute_name'];
//        }
//        return $attributes_name;
//        return in_array("wasem",$attributes_name)*8;
//return collect($this->returnError(5,"w"))->toArray()['original']['status']*8;
//        return [true,"wasem"];
//        $city_id=1;
//        $properties=Property::whereHas('region',function($q) use ($city_id){
//            $q->where('city_id',$city_id);
//        })
//            ->with([
//                'advertisement',
//                'ratings',
//                'directions',
//                'region',
//                'pledge_type',
//                'ownership_type',
//                'rooms',
//                'features',
//                'detailed_attributes',
//                'medias'
//            ])
//            ->get();
//        return PropertyResource::collection($properties);
//        $id=1;
//        $all=PropertyMainCategory::with('sub_categories')->get();
//        return PropertyCategorisResource::collection($all);


//            $r=Country::with('cities.regions')->get();
//            return CountryResource::collection($r);

        return ComplaintResponse::where('id',4)->first()->delete();
        $c=Complaint::where('id',1)->with('reply')->first();
        $c->reply()->create([
            'response_text'=>"tessst"
        ]);
        return $c;
//        $folder_path='lang';
////        $folderMobile=base_path('app/Repositories/Mobile');
////        $filesMobile=File::directories($folderMobile);
//            $directories_name=File::directories(base_path($folder_path));
//            $languages=collect($directories_name)->map(function ($directory) use ($folder_path) {
//                return Str::after($directory, $folder_path.'\\');
//            });
/// //        try {
//            $a=[
//                'publication_type'=>'sale',
//                'main_category_id'=>1,
//                'sub_category_id'=>1,
//                'min_area'=>100,
//                'max_area'=>300,
//                'ownership_type_id'=>1,
//                'min_price'=>10000,
//                'max_price'=>3000000,
//                'country_id'=>1,
//                'city_id'=>1,
//                'region_id'=>1,
//                'secondary_address'=>'test',
//                'features'=>[1,2,3],
//                'directions'=>[1,3],
//                'rent_type'=>'monthly',
//
//                'detailed_attributes'=>[
//                        'level_location'=>2,
//                        'levels_count'=>3,
//                        'water_source'=>'well',
//                        'permissible_construction_ratio'=>50,
//                        'number_of_floors_that_can_be_built'=>4,
//                        'soil_type'=>'c',
//                        'number_of_shipping_doors'=>5,
//                        'number_of_entries'=>1,
//                        'number_of_exits'=>1
//                ],
//
//                'pledge_type_id'=>2,
//                'min_age'=>3,
//                'max_age'=>10,
//                'rooms'=>[
//                    ['type_id'=>1,'number'=>2]
//                    ,['type_id'=>2,'number'=>3]
//                ]
//            ];
//            $a=[
////                'publication_type'=>'rent',
////                'rent_type'=>'yearly',
////                'min_price'=>10000,
////                'max_price'=>100000,
//
//            ];
//
//            $properties=Property::query()
//                ->select([
//                    'id',
//                    'serial_number',
//                    'area',
//                    'price_history',
//                    'publication_type',
//                    'rent_price',
//                    'age',
//                    'status',
//                    'secondary_address',
//                    'price_history_price',
//                    'rent_price_type',
//                    'deleted_at',
//                    'map_points',
//                    'region_id',
//                    'ownership_type_id',
//                    'pledge_type_id',
//                    'category_id',
//                    'price_history_price',
//                    'rent_price_type',
//                ])
//                ->with([
//                    'advertisement',
//                    'ownership_type',
//                    'pledge_type',
//                    'sub_category.main_category',
//                    'region.city.country',
//                    'features.feature',
//                    'directions',
//                    'rooms.room_type',
//                    'ratings',
//                    'detailed_attributes',
//                    'medias'
//                ])
////                    ->with(['sub_category:id', 'region.city:id', 'features:feature_id', 'directions:directions.id'])
//                    ->when(isset($a['publication_type']), function ($q) use ($a) {
//                        $q->where('publication_type', $a['publication_type']);
//                    })
//                    ->when(isset($a['main_category_id']), function ($q) use ($a) {
//                        $q->whereHas('sub_category', function ($q) use ($a) {
//                            $q->where('main_category_id', $a['main_category_id']);
//                        });
//                    })
//                    ->when(isset($a['sub_category_id']), function ($q) use ($a) {
//                        $q->where('category_id', $a['sub_category_id']);
//                    })
//                    ->when(isset($a['min_area']), function ($q) use ($a) {
//                        $q->where('area', '>=', $a['min_area']);
//                    })
//                    ->when(isset($a['max_area']), function ($q) use ($a) {
//                        $q->where('area', '<=', $a['max_area']);
//                    })
//                    ->when(isset($a['ownership_type_id']), function ($q) use ($a) {
//                        $q->where('ownership_type_id', $a['ownership_type_id']);
//                    })
//                    ->when(isset($a['min_price']), function ($q) use ($a) {
//                        $q->where('price_history_price', '>=', $a['min_price']);
//                    })
//                    ->when(isset($a['max_price']), function ($q) use ($a) {
//                        $q->where('price_history_price', '<=', $a['max_price']);
//                    })
//                    ->when(isset($a['country_id']), function ($q) use ($a) {
//                        $q->whereHas('region.city', function ($q) use ($a) {
//                            $q->where('country_id', $a['country_id']);
//                        });
//                    })
//                    ->when(isset($a['city_id']), function ($q) use ($a) {
//                        $q->whereHas('region', function ($q) use ($a) {
//                            $q->where('city_id', $a['city_id']);
//                        });
//                    })
//                    ->when(isset($a['region_id']), function ($q) use ($a) {
//                        $q->where('region_id', $a['region_id']);
//                    })
//                    ->when(isset($a['pledge_type_id']), function ($q) use ($a) {
//                        $q->where('pledge_type_id', $a['pledge_type_id']);
//                    })
//                    ->when(isset($a['min_age']), function ($q) use ($a) {
//                        $q->where('age', '>=', $a['min_age']);
//                    })
//                    ->when(isset($a['max_age']), function ($q) use ($a) {
//                        $q->where('age', '<=', $a['max_age']);
//                    })
//                    ->when(isset($a['secondary_address']), function ($q) use ($a) {
//                        $q->whereRaw("MATCH(secondary_address) AGAINST(? IN BOOLEAN MODE)", [$a['secondary_address']]);
//                    })
//                    ->when(isset($a['features']) && !empty($a['features']), function ($q) use ($a) {
//                        $q->whereHas('features', function ($q) use ($a) {
//                            $q->whereIn('feature_id', $a['features']);
//                        });
//                    })
//                    ->when(isset($a['directions']) && !empty($a['directions']), function ($q) use ($a) {
//                        $q->whereHas('directions', function ($q) use ($a) {
//                            $q->whereIn('directions.id', $a['directions']);
//                        });
//                    })
//                    ->when(isset($a['rent_type']) && $a['rent_type'], function ($q) use ($a) {
//                        $q->where('rent_price_type', $a['rent_type']);
//                    })
//                    ->when(isset($a['detailed_attributes']), function ($q) use ($a) {
//                        $q->whereHas('detailed_attributes', function ($q) use ($a) {
//                            $q->where(function ($sub) use ($a) {
//                                foreach ($a['detailed_attributes'] as $key => $value) {
//                                    $sub->orWhere([
//                                        'key' => $key,
//                                        'value' => $value
//                                    ]);
//                                }
//                            });
//                        });
//                    })
////                    ->when(isset($a['level_location']) && $a['level_location'], function ($q) use ($a) {
////                        $q->whereHas('detailed_attributes', function ($q) use ($a) {
////                            $q->where('key', 'level location')->where('value', $a['level_location']);
////                        });
////                    })
////                    ->when(isset($a['levels_count']) && $a['levels_count'], function ($q) use ($a) {
////                        $q->whereHas('detailed_attributes', function ($q) use ($a) {
////                            $q->where('key', 'levels count')->where('value', $a['levels_count']);
////                        });
////                    })
////                    ->when(isset($a['water_source']) && $a['water_source'], function ($q) use ($a) {
////                        $q->whereHas('detailed_attributes', function ($q) use ($a) {
////                            $q->where('key', 'water source')->where('value', $a['water_source']);
////                        });
////                    })
////                    ->when(isset($a['permissible_construction_ratio']), function ($q) use ($a) {
////                        $q->whereHas('detailed_attributes', function ($q) use ($a) {
////                            $q->where('key', 'permissible construction ratio')->where('value', $a['permissible_construction_ratio']);
////                        });
////                    })
////                    ->when(isset($a['number_of_floors_that_can_be_built']), function ($q) use ($a) {
////                        $q->whereHas('detailed_attributes', function ($q) use ($a) {
////                            $q->where('key', 'number of floors that can be built')->where('value', $a['number_of_floors_that_can_be_built']);
////                        });
////                    })
////                    ->when(isset($a['soil_type']) && $a['soil_type'], function ($q) use ($a) {
////                        $q->whereHas('detailed_attributes', function ($q) use ($a) {
////                            $q->where('key', 'soil type')->where('value', $a['soil_type']);
////                        });
////                    })
////                    ->when(isset($a['number_of_shipping_doors']), function ($q) use ($a) {
////                        $q->whereHas('detailed_attributes', function ($q) use ($a) {
////                            $q->where('key', 'number of shipping doors')->where('value', $a['number_of_shipping_doors']);
////                        });
////                    })
////                    ->when(isset($a['number_of_entries']), function ($q) use ($a) {
////                        $q->whereHas('detailed_attributes', function ($q) use ($a) {
////                            $q->where('key', 'number of entries')->where('value', $a['number_of_entries']);
////                        });
////                    })
////                    ->when(isset($a['number_of_exits']), function ($q) use ($a) {
////                        $q->whereHas('detailed_attributes', function ($q) use ($a) {
////                            $q->where('key', 'number of exits')->where('value', $a['number_of_exits']);
////                        });
////                    })
//                    ->when(isset($a['rooms']), function ($q) use ($a) {
//                        $q->whereHas('rooms', function ($q) use ($a) {
//                            $q->where(function ($sub) use ($a) {
//                                foreach ($a['rooms'] as $room) {
//                                    $sub->orWhere([
//                                        'room_type_id' => $room['type_id'],
//                                        'count' => $room['number']
//                                    ]);
//                                }
//                            });
//                        });
//                    })
////                    ->get();
//                    ->paginate(25);
//
//            return $this->returnData('advertising',PropertyResource::collection($properties));
////            $classification_id=Classification::where('name','properties')->first()->id;
////            $attributes=collect(ConfigAttributesResource::collection(ConfigAttribute::where('classification_id',$classification_id)->select('attribute_name')->whereNot('attribute_name','LIKE','%.*.%')->get()))->pluck('attribute_name')->toArray();
////            $rules=[];
////            foreach ($attributes as $attribute){
////                $rules[$attribute['attribute_name']]=json_decode($attribute['rules']);
////            }
////            return $this->returnData('attributes',$attributes);
//        }catch (\Exception $ex) {
//            return $this->returnError($ex->getCode(), $ex->getMessage());
//        }
    }
    public function testpermissionuser($id)
    {

        $user_permission=User::findOrFail($id)->permissions->pluck('title')->toArray();
        $role_user_permission=User::findOrFail($id)->roles()->with('permissions')->get()->pluck('permissions')->collapse()->merge($user_permission);
return $user_permission;
        return array_merge($user_permission->toArray(),$role_user_permission->toArray());
    }
    public function testG()
    {



        $tasks=Employee::where('id',1)->first()->my_tasks()->where('is_payment',0)->get();
        foreach ($tasks as $task)
        {
            $task->is_payment=true;
            $task->save();
        }
        return $this->returnSuccessMessage("Payment Successfully");
        $faker=Factory::create('nl_NL');
        $full_name=$faker->name('male');
        $addrress=$faker->address();
        $user_name=str_replace(' ', '_',str_replace('.', '', Str::lower($full_name)));
        $email=$user_name."@gmail.com";
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $password = Str::random(8, $characters);
        do{
            $phone_number=str_replace(' ','',$faker->phoneNumber());
        }while(!str_starts_with($phone_number,"+31"));


        return [$full_name,$phone_number,$addrress,$user_name,$email,$password,$this->generateRandomObjects(10)];
    }


    public function generate(Request $request)
    {
        $text = request()->text; // الحصول على النص المدخل

        // تحويل النص إلى مصفوفة من السطور
        $lines = explode("\n", $text);

        // إنشاء جدول HTML
        $html = '<table><thead><tr><th>الأسبوع</th><th>اليوم</th><th>الموقع</th><th>الوقت</th><th>المشروع</th><th>الساعات</th></tr></thead><tbody>';

        $dd=[];
        foreach ($lines as $line) {
            // فصل البيانات في كل سطر
            $data = explode(' ', $line);

            $dd[]=explode("\r",$line)[0];
            // بناء صف الجدول
            $html .= '<tr>';
            $html .= '<td>' . $data[0] . '</td>';
            // ... باقي الأعمدة
            $html .= '</tr>';
        }


        // تحويل HTML إلى PDF باستخدام Dompdf
        // ... كما في المثال السابق

            $html .= '</tbody></table>';

        $data_new=array_filter($dd);
//            ,function ($item){
//            return !empty($item);
//        });
        $days=[
"zondag",
"maandag",
"dinsdag",
"woensdag",
"donderdag",
"vrijdag",
"zaterdag"
        ];

//        return 8*Str::contains($days[6],"Zaterdag");
        $day=[];
        $w="";

        foreach ($data_new as $value)
        {

//            if (Str::contains($value,"week"))
//            {
//             $w=$value;
//            continue;
//            }
//
//            if ($this->is_contains_array($days,$value))
//            {
//                $day[]=$value;
//            }
            $element=Str::lower($value);
          if (!Str::contains($element," tot ")&&!Str::contains($element,"uren")&&!Str::contains($element,"totaal")&&!Str::contains($element,"total"))
          {
              $string =$element;
        $trimmed_string = trim($string);
              $dw[]=$trimmed_string;
              continue;
          }
            $dw[]=$element;

        }


        return $dw;
//        $string =$dw[1];
//        $trimmed_string = trim($string);
//        return [$trimmed_string];
        $wasem=[];
        $d='';
        $total='';
        $place='placewasem';
        $tig='';
        $p='';
        $uren='';
        for ($i=0;$i<count($dw);$i++)
        {
             if (Str::contains(Str::lower($dw[$i]),"week"))
            {
                $string =$dw[$i];
                preg_match('/(\d+)/', $string, $matches);
                $number = $matches[1];
             $w=$number;
            continue;
            }

             if ($this->is_contains_array($days,Str::lower($dw[$i])))
             {
                 $day[]=$dw[$i];
                 $d=$dw[$i];
                 $j=0;
                 $f=[];
                 do
                 {
                     $j++;
                     echo $dw[$i+1];
//                     if (Str::contains($dw[$i+1]," tot "))
//                     {
//                         $tig=$dw[$i+1];
//                     }
//                     if (Str::contains($dw[$i+1],"uren"))
//                     {
//                         return "wasem";
//                         $uren=$dw[$i+1];
//                     }
//                     if (Str::contains($dw[$i+1],"totaal")||
//                         Str::contains($dw[$i+1],"total"))
//                     {
//                         $total=$dw[$i+1];
//                         break;
//                     }
//                     if (!Str::contains($dw[$i+1]," tot ")&&!Str::contains($dw[$i+1],"uren"))
//                     {
//                         $place=$dw[$i+1];
//                     }
//                     if (Str::contains($place,'placewasem'))
//                     {
//                         $wasem[]=[
//                             'day'=>$dw[$i],
//                             'tig'=>$tig,
//                             'uren'=>$uren,
//                             'place'=>''
//                         ];
//                     }else{
//                         $wasem[]=[
//                             'day'=>$dw[$i],
//                             'tig'=>$tig,
//                             'uren'=>$uren,
//                             'place'=>$place
//                         ];
//                     }
//
//                     $place='placewasem';


                 }while(!$this->is_contains_array($days,$dw[$i+$j]));


                 $wasem[]=[
                     'day'=>$dw[$i],
                     'data'=>$f
             ];
                 array_push($f,[]);
//                 continue;
             }
        }
        return[
          'week'=>$w,
          'day'=>$day,
            'data'=>$wasem,
            'total'=>$total
        ];
//        return view('table',compact('dw'));

    }


    public function uploadFile(Request $request)
    {
        $filepath = request()->file->getRealPath();
//        return $filepath;
             // Get file path from request

        $apikey = '95d2af7624f42c9d8be8e5f37c615bcf8f04d7c738b6fc5b7d04c34fea618ebf5ff2a0b85aca2960';
        $reference = request()->reference; // Optional reference ID

        $client = new HttpClient();
        $fileContents = file_get_contents($filepath);

        try {

            $response = $client->post('https://api.wassenger.com/v1/files', [
                'headers' => [
                    'Token' => $apikey,
                ],
                'multipart' => [
//                    [
//                        'name' => 'file',
//                        'contents' => $fileContents, // Open file for reading
////                        'contents' => fopen($filepath, 'r'), // Open file for reading
//                        'filename' => request()->file->getClientOriginalName(), // Use original file name
//                    ],
//                    [
//                        'name' => 'phone',
//                        'type' => 'text',
//                        'value' => '+963945541233'
//                    ],
//                    [
//                        'name' => 'message',
//                        'type' => 'text',
//                        'value' => 'This is a media message example 😀'
//                    ]
                    'phone'=>'+963945541233',
                    'message'=>'This is a media message example 😀',
                    'media'=>['file'=>$fileContents]
//                {
//  "phone": "+31687957725",
//  "message": "This is a media message example 😀",
//  "media": {
//                "file": "UPLOADED FILE ID GOES HERE"
//  }
//}
                ],
                'query' => [
                    'reference' => $reference,
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode >= 400) {
                return response()->json(['message' => 'Invalid request or unsupported file to upload'], $statusCode);
            }

            $data = json_decode($response->getBody()->getContents(), true);
            $fileId = $data[0]['id'];

            return response()->json(['message' => 'File uploaded successfully with ID: ' . $fileId, 'file_id' => $fileId]);
        } catch (TransferException $e) {
            return response()->json(['message' => 'Upload failed: ' . $e->getMessage()], 500); // Handle exceptions
        }
    }
}
