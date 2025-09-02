<?php

namespace App\Repositories\Mobile\Services;

use App\Http\Resources\OrderResource;
use App\Http\Resources\ProviderResource;
use App\Http\Resources\ServiceResource;
use App\Jobs\SendNotification;
use App\Models\CategoryService;
use App\Models\Order;
use App\Models\ServiceProvider;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServicesUserRepository implements ServicesUserRepositoryInterface
{
    use GeneralTrait;
    public function get_all_categories_services()
    {
        // TODO: Implement get_all_categories_services() method.
        try {
            $categories_services=CategoryService::with('translation')->get();
            return $this->returnData('services',ServiceResource::collection($categories_services));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_all_categories_services_with_service_providers()
    {
        // TODO: Implement get_all_categories_services_with_service_providers() method.
        try {
            $categories_services=CategoryService::with(['service_providers','translation'])
//                ->whereHas('service_providers',function($q){
//                    $q->where('status','accept')
//                    ->whereHas('order',function($q){
//                        $q->where('status','posted');
//                    });
//                })
                ->get();
            return $this->returnData('services',ServiceResource::collection($categories_services));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_service_providers_by_category_id($request)
    {
        // TODO: Implement get_service_providers_by_category_id() method.
        try {
            $service_providers=CategoryService::where('id',$request->category_id)->first()
                ->service_providers()
                ->with([
                    'region',
                    'ratings',
                ])
                ->where('status','accept')
                ->whereHas('order',function($q){
                    $q->where('status','posted');
                })
                ->get();
            return $this->returnData('service_providers',ProviderResource::collection($service_providers));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function send_order_add_service_provider($request)
    {
        // TODO: Implement send_order_add_service_provider() method.
        try {
            DB::beginTransaction();
            $user=Auth::user();
            if (Str::lower($user->roles()->first()->title)===Str::lower('Merchant')){
                if ($user->merchant_register_order->status==='pending'){
                    DB::rollBack();
                    return $this->returnError(403403,'Your membership request has not been processed yet.');
                }
                if ($user->merchant_register_order->status==='unacceptable'){
                    DB::rollBack();
                    return $this->returnError(403403,'Your membership application has not been accepted. Please check with the administration or your notifications to find out why.');
                }
            }
            $count=$user->orders()->where('for_service_provider',true)->count();
//            if ($count==0)
//            {
                $first_name=request()->first_name;
                $last_name=request()->last_name;
                $region_id=request()->region_id;
                $secondary_address=request()->secondary_address;
                $phone_number=request()->phone_number;
                $email=request()->email?request()->email:'';
                $description=request()->description?request()->description:'';
                $services=request()->services;
                $business_image_url='service_provider_default.png';
                if ($request->hasFile('image')){
                    $business_image_url=$this->UploadeImage('service_provider',request()->image);
                }
                $provider=ServiceProvider::create([
                    'first_name'=>$first_name,
                    'last_name'=>$last_name,
                    'business_image_url'=>$business_image_url,
                    'region_id'=>$region_id,
                    'secondary_address'=>$secondary_address,
                    'phone_number'=>$phone_number,
                    'email'=>$email,
                    'description'=>$description,
                ]);
                $provider->categories()->sync($services);
                $order=$provider->order()->create([
                    'serial_number'=>$this->generate_serialnumber(Order::class),
                    'date'=>Carbon::now()->toDate(),
                    'user_id'=>$user->id,
                    'for_service_provider'=>true
                ]);

            //send notifications to super admin and admin
            $title = trans('notifications.send_order_add_service_provider');
            $body =trans('notifications.send_order_add_service_provider_body')." : ".$order->serial_number;
            $type = 'info';
            $users=User::with('device_tokens')
                ->whereHas('roles',function($q){
                    $q->where(function ($query){
                        $query->where('title','super_admin')
                            ->orWhere('title','admin');
                    });
                })->get();

            $data=[
                'users'=>[$users],
                'title'=>$title,
                'body'=>$body,
                'type'=>$type
            ];
            dispatch(new SendNotification($data));

                DB::commit();
                return $this->returnSuccessMessage('Service provider added successfully');
//            }
//            DB::rollBack();
//            return $this->returnError(500,'Working Error');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_my_orders_add_service_provider()
    {
        // TODO: Implement get_my_orders_add_service_provider() method.
        try {
            $user=Auth::user();
            $order=$user->orders()
                ->with('orderable')
                ->where('for_service_provider',true)->get();
            return $this->returnData('order',OrderResource::collection($order));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function edited_my_service_provider($request)
    {
        // TODO: Implement edited_my_service_provider() method.
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $provider_id=request()->provider_id;
            $provider=ServiceProvider::where('id',$provider_id)->first();
            if (!$provider){
                return $this->returnError(404,'Provider Not Found');
            }
            $order_id=$provider->order->id;
            $exists=$this->relationship_exists($user->id,$order_id,'orders',User::class);
            if ($exists)
            {
                $provider->first_name=request()->first_name?request()->first_name:$provider->first_name;
                $provider->last_name=request()->last_name?request()->last_name:$provider->last_name;
                $provider->region_id=request()->region_id?request()->region_id:$provider->region_id;
                $provider->secondary_address=request()->secondary_address?request()->secondary_address:$provider->secondary_address;
                $provider->phone_number=request()->phone_number?request()->phone_number:$provider->phone_number;
                $provider->email=request()->email?request()->email:$provider->email;
                $provider->description=request()->description?request()->description:$provider->description;
                if ($request->hasFile('image')){
                    $provider->business_image_url=$this->UploadeImage('service_provider',request()->image);
                }
                $provider->save();
                if ($request->has('services')&&is_array($request->services)){
                    $provider->categories()->sync($request->services);
                }
                DB::commit();
                return $this->returnSuccessMessage('Your Service provider edited successfully');
            }
            DB::rollBack();
            return $this->returnError(404,'Working Error');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function deleted_my_service_provider($request)
    {
        // TODO: Implement deleted_my_service_provider() method.
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $provider_id=request()->provider_id;
            $provider=ServiceProvider::where('id',$provider_id)->first();
            if (!$provider){
                return $this->returnError(404,'Provider Not Found');
            }
            $order_id=$provider->order->id;
            $exists=$this->relationship_exists($user->id,$order_id,'orders',User::class);
            if ($exists)
            {
                $provider->order()->delete();
                $provider->delete();
                DB::commit();
                return $this->returnSuccessMessage('Your Service provider deleted successfully');
            }
            DB::rollBack();
            return $this->returnError(404,'Working Error');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function edited_activation_my_service_provider($request)
    {
        // TODO: Implement edited_activation_my_service_provider() method.
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $provider_id=request()->provider_id;
            $provider=ServiceProvider::where('id',$provider_id)->first();
            if (!$provider){
                return $this->returnError(404,'Provider Not Found');
            }
            $order_id=$provider->order->id;
            $exists=$this->relationship_exists($user->id,$order_id,'orders',User::class);
            if ($exists)
            {
                $order_status=$provider->order->status;
                if (Str::contains($order_status,'posted'))
                {
                    if ($provider->status=="accept"){
                        $provider->status="unaccept";
                        $provider->save();
                        DB::commit();
                        return $this->returnSuccessMessage('Inactivated Your Service provider Successfully');
                    }elseif ($provider->status=="unaccept"){
                        $provider->status="accept";
                        $provider->save();
                        DB::commit();
                        return $this->returnSuccessMessage('Activated Your Service provider Successfully');
                    }
                }
            }
            DB::rollBack();
            return $this->returnError(404,'Working Error');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function rate_service_provider($request)
    {
        // TODO: Implement rate_service_provider() method.
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $provider_id=request()->provider_id;
            $score=$request->score;
            $provider=ServiceProvider::where('id',$provider_id)->first();
            if (!$provider){
                return $this->returnError(404,'Provider Not Found');
            }
            $order_id=$provider->order->id;
            $exists=$this->relationship_exists($user->id,$order_id,'orders',User::class);
            if (!$exists)
            {
                if ($provider->status=="accept"&&$provider->order->status==='posted')
                {
                    $user->from_me_ratings()->create([
                        'rating_score'=>$score,
                        'ratingable_type'=>$provider->order->orderable_type,
                        'ratingable_id'=>$provider->order->orderable_id,
                    ]);
                    DB::commit();
                    return $this->returnSuccessMessage('Rated successfully');
                }
            }
            DB::rollBack();
            return $this->returnError(404,'Working Error');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }
}
