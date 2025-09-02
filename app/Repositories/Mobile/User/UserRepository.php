<?php

namespace App\Repositories\Mobile\User;

use App\Http\Resources\IdKeyValueResource;
use App\Http\Resources\MyFavoriteResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserProfileResource;
//use App\Jobs\mailupdateemail;
use App\Http\Resources\UserResource;
use App\Jobs\mailupdateemailJob;
use App\Models\Advertisement;
use App\Models\Favorite;
use App\Models\SystemContactInformation;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    use GeneralTrait;
    public function add_interests($request)
    {
        // TODO: Implement add_interests() method.
    }

    public function change_interests($request)
    {
        // TODO: Implement change_interests() method.
    }

    public function get_my_notifications()
    {
        // TODO: Implement get_my_notifications() method.
        try {
            $user = Auth::user();
            $data=[
                'count_not_read'=>$user->notifications()->where('is_read',0)->count(),
                'notifications'=>NotificationResource::collection($user->notifications()->orderByDesc('created_at')->get())
            ];
            return $this->returnData('notifications',$data,'');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function read_notifications()
    {
        // TODO: Implement read_notifications() method.
        try {
            DB::beginTransaction();
        $notifications=Auth::user()->notifications()->where('is_read',0)->get();
        if ($notifications->isNotEmpty()) {
            foreach ($notifications as $notification)
            {
                $notification->is_read=1;
                $notification->save();
            }
            DB::commit();
            return $this->returnSuccessMessage(trans('messages.read_notifications'));
        }
            return $this->returnError(404,'Notifications not found');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_system_contact_information()
    {
        // TODO: Implement get_system_contact_information() method.
        try {
            $system_contact_information=SystemContactInformation::with('translation')->get();
            return $this->returnData('system_contact_information',IdKeyValueResource::collection($system_contact_information),'');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_my_info($request)
    {
        // TODO: Implement update_my_info() method.
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user->first_name=$request->first_name??$user->first_name;
            $user->last_name=$request->last_name??$user->last_name;
            $user->region_id=$request->region_id??$user->region_id;
            $user->secondary_address=$request->secondary_address??$user->secondary_address;
            $user->phone_number=$request->phone_number??$user->phone_number;
            $user->save();

            if ($request->hasFile('image'))
            {
                $image=$request->image;
                $new=$this->UploadeImage('employee',$image);
                $url=$user->image_url;
                if (Str::startsWith($url,'/'))
                {
                    File::delete(public_path($this->after('/',$url)));
                }else
                {
                    File::delete(public_path($url));
                }
                $user->image_url=$new;
                $user->save();
            }
            if ($request->has('email')){
                mailupdateemailJob::dispatch($request->email,$user);
                DB::commit();
                return $this->returnSuccessMessage('Updated Your Information Successfully,And A verification code has been sent to your email, please see Gmail.');
            }
            DB::commit();
            return $this->returnData('user',new UserResource($user->load(['region'])),'Updated Your Information Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function add_to_favourites($request)
    {
        // TODO: Implement add_to_favourites() method.
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $advertisement_id=$request->advertisement_id;
            $advertisement=Advertisement::findOrFail($advertisement_id);
            $subscribe_id=$advertisement->subscribe->id;
            $exists=$this->relationship_exists($user->id,$subscribe_id,'subscribes',User::class);
            if (!$exists){
                $advertisementable=$advertisement->advertisementable;
                if ($advertisementable->status==='active'&&$advertisementable->order->status==='posted'
                    &&$advertisement->active)
                {
                    $secondId=$advertisement->advertisementable_id;
                    $firstId=$user->id;
                    $ex= User::whereHas('favorite', function ($query) use ($secondId) {
                        $query->where('favoriteable_id', $secondId);
                    })->where('id', $firstId)->exists();
                    if ($ex){
                        $ob=$user->favorite()->where('favoriteable_id',$secondId)->first();
                        $favorite_id=$ob->id;
                        Favorite::where('id',$favorite_id)->delete();
                        DB::commit();
                        return $this->returnSuccessMessage('Deleted From Favorites successfully');
                    }else{
                        $user->favorite()->create([
                            'favoriteable_type'=>$advertisement->advertisementable_type,
                            'favoriteable_id'=>$advertisement->advertisementable_id,
                        ]);
                        DB::commit();
                        return $this->returnSuccessMessage('Add in Favorites successfully');
                    }
                }
            }
            DB::rollBack();
            return $this->returnError('500', 'Working Error');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_my_favourites()
    {
        // TODO: Implement get_my_favourites() method.
        try {
            $user = Auth::user();
            $my_favorites=$user->favorite()->with(['favoriteable','favoriteable.order.user'])->get();
            if ($my_favorites->isNotEmpty()) {
                return $this->returnData('my_favorites',MyFavoriteResource::collection($my_favorites->load(['favoriteable','favoriteable.order.user'])));
            }
            return $this->returnData('my_favorites',[],'Your Favorites not found');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_from_my_favourites($request)
    {
        // TODO: Implement delete_from_my_favourites() method.
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $favorite_id=$request->favorite_id;
            $exists=$this->relationship_exists($user->id,$favorite_id,'favorite',User::class);
            if ($exists){
                Favorite::where('id',$favorite_id)->delete();
                DB::commit();
                return $this->returnSuccessMessage("delete your favorite successfully");
            }
           DB::rollBack();
            return $this->returnError('500', 'Working Error');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function locked_application($request)
    {
        // TODO: Implement locked_application() method.
    }

    public function check_password_locked_application($request)
    {
        // TODO: Implement check_password_locked_application() method.
    }

    public function delete_my_account()
    {
        // TODO: Implement delete_my_account() method.
        try {
            DB::beginTransaction();
            //$device_token=$request->device_token;
//        $user_update=Auth::user();
//        $user_update->my_devices_token()->where('token', $device_token)->delete();
            $user_card = Auth::user()->token();
            $user=$user_card->user;
            $user_card->revoke();
            $user->delete();
            DB::commit();
            return $this->returnSuccessMessage('Your account has been Deleted Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }

    public function get_user_profile($request)
    {
        // TODO: Implement get_user_profile() method.
        try {
            $user_id=$request->user_id;
//            if ($user_id!=Auth::id())
//            {
                $user=User::where('id',$user_id)->with('orders')->first();
                if ($user)
                {
                    return $this->returnData('user_profile',new UserProfileResource($user));
                }
                return $this->returnError(404,'User not found');
//            }
//             return $this->returnError(500,'Working Error');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function rate_user($request)
    {
        // TODO: Implement rante_user() method.
    }


    public function get_my_notifications_by_device_token($request)
    {
        // TODO: Implement get_my_notifications_by_device_token() method.
        try {
            $device_token =request()->device_token;
            $user=User::whereHas('device_tokens',function ($query) use ($device_token){
               $query->where('token',$device_token);
            })
            ->first();
            if (!$user)
            {
                $data=[
                    'count_not_read'=>0,
                    'notifications'=>[]
                ];
                return $this->returnData('notifications',$data,'No Notifications');
            }
            $data=[
                'count_not_read'=>$user->notifications()->count(),
                'notifications'=>NotificationResource::collection($user->notifications()->orderByDesc('created_at')->get())
            ];
            return $this->returnData('notifications',$data,'');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
