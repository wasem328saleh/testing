<?php

namespace App\Repositories\Dashboard\User;

use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserProfileResource;
use App\Jobs\mailupdateemailJob;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminUserRepository implements AdminUserRepositoryInterface
{
    use GeneralTrait;
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
            return $this->returnSuccessMessage('Updated Your Information Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
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

    public function get_user_profile($request)
    {
        // TODO: Implement get_user_profile() method.
        try {
            $user_id=$request->user_id;
            if ($user_id!=Auth::id())
            {
                $user=User::where('id',$user_id)->with('orders')->first();
                if ($user)
                {
                    return $this->returnData('user_profile',new UserProfileResource($user));
                }
                return $this->returnError(404,'User not found');
            }
            return $this->returnError(500,'Working Error');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
