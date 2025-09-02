<?php

namespace App\Repositories\Dashboard\Services;

use App\Http\Resources\OrderResource;
use App\Http\Resources\ProviderResource;
use App\Http\Resources\ServiceResource;
use App\Jobs\SendNotification;
use App\Models\CategoryService;
use App\Models\Order;
use App\Models\Role;
use App\Models\ServiceProvider;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ServicesManagementRepository implements ServicesManagementRepositoryInterface
{
    use GeneralTrait;

    public function get_all_categories()
    {
        // TODO: Implement get_all_categories_services() method.
        try {
            $categories_services=CategoryService::with('translation')->get();
            return $this->returnData('services',ServiceResource::collection($categories_services));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }
    public function get_all_categories_services()
    {
        // TODO: Implement get_all_categories_services() method.
        try {
            $categories_services=CategoryService::with(['service_providers','translation'])->get();
            return $this->returnData('services',ServiceResource::collection($categories_services));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function add_categories_services($request)
    {
        // TODO: Implement add_categories_services() method.
        try {
            DB::beginTransaction();
            $name=request()->name;
            $image_url='fake_image_services/category_service/default.png';
            if (request()->hasFile('image')) {
                $image = request()->file('image');
                $image_url=$this->UploadeImage('categories_services',$image);
            }
            CategoryService::create([
                'name'=>$name,
                'image_url'=>$image_url,
            ]);
            DB::commit();
            return $this->returnSuccessMessage('services added successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function update_categories_services($request)
    {
        // TODO: Implement update_categories_services() method.
        try {
            DB::beginTransaction();
            $category_id=request()->category_id;
            $category=CategoryService::where('id',$category_id)->first();
            if (!$category) {
                DB::rollBack();
                return $this->returnError(404,'category not found');
            }
            if (request()->hasFile('image')) {
                $image = request()->file('image');
                $image_url=$this->UploadeImage('categories_services',$image);
                $url=$category->image_url;
                if (Str::startsWith($url,'/'))
                {
                    File::delete(public_path($this->after('/',$url)));
                }else
                {
                    File::delete(public_path($url));
                }
                $category->image_url=$image_url;
            }
            if (request()->name!=null)
            {
                $name=request()->name;
                $category->name=$name;
            }
            $category->save();
            DB::commit();
            return $this->returnSuccessMessage('services updated successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function delete_categories_services($request)
    {
        // TODO: Implement delete_categories_services() method.
        try {
            DB::beginTransaction();
            $category_id=request()->category_id;
            if ($category_id==1)
            {
                DB::rollBack();
                return $this->returnError(500,'Working Error');
            }
            $category=CategoryService::where('id',$category_id)->first();
            if (!$category) {
                DB::rollBack();
                return $this->returnError(404,'category not found');
            }
            $providers=$category->service_providers;
            if ($providers->isNotEmpty()) {
                foreach ($providers as $provider) {
                    $provider->categories()->sync(1);
                }
            }
            $category->delete();
            DB::commit();
            return $this->returnSuccessMessage('services deleted successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_all_providers_by_service_id($id){
        try {
            $categories_services=CategoryService::where('id',$id)->first();
            return $this->returnData('services',ProviderResource::collection($categories_services->service_providers->load([
                'region',
                'ratings',
                'categories'
            ])));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }
    public function add_providers_services($request)
    {
        // TODO: Implement add_providers_services() method.
        try {
            DB::beginTransaction();
            $admin_id=Role::where('title','super_admin')->with('users')->first()->users()->first()->id;
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
                'isActive'=>1,
                'status'=>'accept'
            ]);
            $provider->categories()->sync($services);
            $provider->order()->create([
                'serial_number'=>$this->generate_serialnumber(Order::class),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$admin_id,
                'for_service_provider'=>true,
                'status'=>'posted'
            ]);
            DB::commit();
            return $this->returnSuccessMessage('Service provider added successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function update_providers_services($request)
    {
        // TODO: Implement update_providers_services() method.
        try {
            DB::beginTransaction();
            $provider_id=request()->provider_id;
            $provider=ServiceProvider::where('id',$provider_id)->first();
            if (!$provider){
                return $this->returnError(404,'Provider Not Found');
            }
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
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function delete_providers_services($request)
    {
        // TODO: Implement delete_providers_services() method.
        try {
            DB::beginTransaction();
            $provider_id=request()->provider_id;
            $provider=ServiceProvider::where('id',$provider_id)->first();
            if (!$provider){
                return $this->returnError(404,'Provider Not Found');
            }
            $provider->order()->delete();
            $provider->delete();
            DB::commit();
            return $this->returnSuccessMessage('Your Service provider deleted successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_all_archived_providers_services()
    {
        // TODO: Implement get_all_archived_providers_services() method.
    }

    public function remove_from_archived($request)
    {
        // TODO: Implement remove_from_archived() method.
    }

    public function unarchived_from_archived($request)
    {
        // TODO: Implement unarchived_from_archived() method.
    }

    public function get_all_orders_services()
    {
        // TODO: Implement get_all_orders_services() method.
        $admin_id=Role::where('title','super_admin')->with('users')->first()->users()->first()->id;

        $orders=OrderResource::collection(Order::with(['orderable','user.personal_identification_papers'])
            ->where('classification_id',null)
            ->where('user_id','!=',$admin_id)
            ->where('status',"pending")
            ->where('for_service_provider',true)
            ->get());

        return $this->returnData('orders', $orders);
    }

    public function accept_order($request)
    {
        // TODO: Implement accept_order() method.
        try {
            DB::beginTransaction();
            $order_id=request()->order_id;
            $order=Order::where('id',$order_id)->first();
            if (!$order){
                DB::rollBack();
                return $this->returnError(404,'Order Not Found');
            }
            if ($order->status==="posted"){
                DB::rollBack();
                return $this->returnError(500,'Order Already Accepted');
            }
            $provider=$order->orderable;
            $provider->status="accept";
            $provider->isActive=true;
            $provider->save();
            $order->status="posted";
            $order->save();

            //send notifications
            $title = trans('notifications.accept_order_add_service_provider');
            $body =trans('notifications.accept_order_add_service_provider_body') . " : " . $provider->first_name." ".$provider->last_name." ";
            $type = 'success';
            $user=$order->user;

            $data=[
                'users'=>[$user],
                'title'=>$title,
                'body'=>$body,
                'type'=>$type
            ];
            dispatch(new SendNotification($data));

            DB::commit();
            return $this->returnSuccessMessage('Order accepted successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function cancel_order($request)
    {
        // TODO: Implement cancel_order() method.
        try {
            DB::beginTransaction();
            $order_id=request()->order_id;
            $order=Order::where('id',$order_id)->first();
            if (!$order){
                DB::rollBack();
                return $this->returnError(404,'Order Not Found');
            }
            if ($order->status==="cancelled"){
                DB::rollBack();
                return $this->returnError(500,'Order Already Cancelled');
            }
            if ($order->status==="posted"){
                DB::rollBack();
                return $this->returnError(500,'Order Already Accepted');
            }
            $provider=$order->orderable;
            $provider->status="unaccept";
            $provider->save();
            $order->status="cancelled";
            $order->reply=request()->reply;
            $order->save();

            //send notifications
            $title = trans('notifications.cancel_order_add_service_provider'). " : " . $provider->first_name." ".$provider->last_name;
            $body =request()->reply;
            $type = 'error';
            $user=$order->user;

            $data=[
                'users'=>[$user],
                'title'=>$title,
                'body'=>$body,
                'type'=>$type
            ];
            dispatch(new SendNotification($data));

            DB::commit();
            return $this->returnSuccessMessage('Order canceled successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }
}
