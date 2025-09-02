<?php

namespace App\Repositories\Dashboard\Admin;

use App\Http\Resources\AllUserResource;
use App\Http\Resources\IdKeyValueResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\SystemContactInformation;
use App\Models\User;
use App\Repositories\Dashboard\Admin\AdminRepositoryInterface;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminRepository implements AdminRepositoryInterface
{
    use GeneralTrait;
    public function get_all_users()
    {
        // TODO: Implement get_all_users() method.
        try {
            DB::beginTransaction();
            $users=Role::where('title','user')->first()->users()
                ->with(['roles','permissions','region.translation','personal_identification_papers'])
                ->get();
            $merchants=Role::where('title','merchant')->first()->users()
                ->with(['roles','permissions','region.translation','personal_identification_papers'])
                ->get();
            $all=array_merge(
                collect(AllUserResource::collection($users))->toArray(),
                collect(AllUserResource::collection($merchants))->toArray()
            );
            DB::commit();
            return $this->returnData('users',$all,'This Is All Users');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function add_user($request)
    {
        // TODO: Implement add_user() method.
        try {
            DB::beginTransaction();
            $first_name=request()->first_name;
            $last_name=request()->last_name;
            $email=request()->email;
            $password=rand(10000000,99999999);
            $secondary_address=request()->secondary_address;
            $phone_number=request()->phone_number;
            $region_id=request()->region_id;
            $user=User::create([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'email'=>$email,
                'password'=>bcrypt($password),
                'image_url'=>$request->hasFile('image_profile')?
                    $this->UploadeImage('Profiles',$request->image_profile):
                    'default_avatar.png',
                'secondary_address'=>$secondary_address,
                'phone_number'=>$phone_number,
                'region_id'=>$region_id,
                'verify'=>true
            ]);
            if (request()->user_type=='user'){
                $user->roles()->sync(2);
            }
            if (request()->user_type=='merchant'){
                $user->roles()->sync(3);
                $identification_papers=$request->identification_papers;
                foreach ($identification_papers as $identification_paper) {
                    $user->personal_identification_papers()->create([
                        'url'=>$this->UploadeImage('identification_paper',$identification_paper)
                    ]);
                }
            }
            $re=collect([
                'email'=>$email,
                'password'=>$password,
            ]);
            DB::commit();
            return $this->returnData('data_information_auth',$re,'Admin Added Successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function edit_user($request)
    {
        // TODO: Implement edit_user() method.
        try {
            DB::beginTransaction();
            $user_id=request()->user_id;
            $admin_id=Role::where('title','super_admin')->with('users')->first()->users()->first()->id;
            if ($user_id==$admin_id){
                DB::rollBack();
                return $this->returnError(500,'working error');
            }
            $user=User::where('id',$user_id)->first();
            if (!$user){
                DB::rollBack();
                return $this->returnError(404,'user not found');
            }
            $user->first_name=$request->first_name??$user->first_name;
            $user->last_name=$request->last_name??$user->last_name;
            $user->email=$request->email??$user->email;
            $user->region_id=$request->region_id??$user->region_id;
            $user->secondary_address=$request->secondary_address??$user->secondary_address;
            $user->phone_number=$request->phone_number??$user->phone_number;
            $user->save();

            if ($request->hasFile('image'))
            {
                $image=$request->image;
                $new=$this->UploadeImage('users',$image);
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
            DB::commit();
            return $this->returnSuccessMessage('Updated User Information Successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function delete_user($request)
    {
        // TODO: Implement delete_user() method.
        try {
            DB::beginTransaction();
            $user_id=request()->user_id;
            $admin_id=Role::where('title','super_admin')->with('users')->first()->users()->first()->id;
            if ($user_id==$admin_id){
                DB::rollBack();
                return $this->returnError(500,'working error');
            }
            $user=User::where('id',$user_id)->first();
            if (!$user){
                DB::rollBack();
                return $this->returnError(404,'user not found');
            }
            $user->roles()->detach();
            $user->delete();
            DB::commit();
            return $this->returnSuccessMessage('Deleted User Successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(),$e->getMessage());
        }
    }
    public function change_status_user($request)
    {
        // TODO: Implement change_status_user() method.
        try {
            DB::beginTransaction();
            $admins_id=Role::where('title','admin')->first()->users()->pluck('user_id')->toArray();
            $user_id=request()->user_id;
            $admin_id=Role::where('title','super_admin')->with('users')->first()->users()->first()->id;
            if ($user_id==$admin_id || in_array(request()->user_id,$admins_id)){
                DB::rollBack();
                return $this->returnError(500,'working error');
            }
            $user=User::where('id',$user_id)->first();
            if (!$user){
                DB::rollBack();
                return $this->returnError(404,'user not found');
            }
            $verify_user=$user->verify;
            if ($verify_user){
                $user->verify=false;
                $user->code=null;
                $user->save();
                DB::commit();
                return $this->returnSuccessMessage('Change Status Successfully');
            }
            $user->verify=true;
            $user->code=null;
            $user->save();
            DB::commit();
            return $this->returnSuccessMessage('Change Status Successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_all_archived_users()
    {
        // TODO: Implement get_all_archived_users() method.
    }

    public function remove_from_archived($request)
    {
        // TODO: Implement remove_from_archived() method.
    }

    public function unarchived_from_archived($request)
    {
        // TODO: Implement unarchived_from_archived() method.
    }

    public function get_all_system_contact_information()
    {
        // TODO: Implement get_all_system_contact_information() method.
        try {
            $system_contact_information=SystemContactInformation::with('translation')->get();
            return $this->returnData('system_contact_information',IdKeyValueResource::collection($system_contact_information),'');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function add_system_contact_information($request)
    {
        // TODO: Implement add_system_contact_information() method.
        try {
            DB::beginTransaction();
            $key=request()->key;
            $value=request()->value;
            SystemContactInformation::create([
                'key'=>$key,
                'value'=>$value
            ]);
            DB::commit();
            return $this->returnSuccessMessage('System Contact Information Added Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function edit_system_contact_information($request)
    {
        // TODO: Implement edit_system_contact_information() method.
        try {
            DB::beginTransaction();
            $info_id=request()->info_id;
            $info=SystemContactInformation::where('id',$info_id)->first();
            if (!$info){
                DB::rollBack();
                return $this->returnError(404,'system contact information not found');
            }
            $info->key=request()->key??$info->key;
            $info->value=request()->value??$info->value;
            $info->save();
            DB::commit();
            return $this->returnSuccessMessage('System Contact Information Updated Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function delete_system_contact_information($request)
    {
        // TODO: Implement delete_system_contact_information() method.
        try {
            DB::beginTransaction();
            $info_id=request()->info_id;
            $info=SystemContactInformation::where('id',$info_id)->first();
            if (!$info){
                DB::rollBack();
                return $this->returnError(404,'system contact information not found');
            }
            $info->delete();
            DB::commit();
            return $this->returnSuccessMessage('System Contact Information deleted Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function send_notification($request)
    {
        // TODO: Implement send_notification() method.
    }
}
