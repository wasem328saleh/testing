<?php

namespace App\Repositories\Mobile\Auth;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserTowResource;
use App\Jobs\ResetPasswordJob;
use App\Jobs\SendMailJob;
use App\Jobs\SendNotification;
use App\Models\MerchantRegisterOrder;
use App\Models\Order;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthUserRepository implements AuthUserRepositoryInterface
{
    use GeneralTrait;

    public function register($request)
    {
        try {
            DB::beginTransaction();
            $user_o=User::where('email',$request->email)->where('verify',false);
            //->exists()
            //->first()
            if ($user_o->exists()&&$user_o->count()>=1) {
                $user_o->delete();
                $user=User::create([
                    'first_name'=>$request->first_name,
                    'last_name'=>$request->last_name,
                    'email'=>$request->email,
                    'password'=>bcrypt($request->password),
                    'image_url'=>$request->hasFile('image_profile')?
                        $this->UploadeImage('Profiles',$request->image_profile):
                        'default_avatar.png',
                    'region_id'=>$request->region_id,
                    'secondary_address'=>$request->secondary_address,
                    'phone_number'=>$request->phone_number,
                ]);
                if (Str::lower($request->user_type)===Str::lower('User')) {
                    $user->roles()->sync(2);
                }
                if (Str::lower($request->user_type)===Str::lower('Merchant')) {
                    $user->roles()->sync(3);
                    $identification_papers=$request->identification_papers;
                    foreach ($identification_papers as $identification_paper) {
                        $user->personal_identification_papers()->create([
                            'url'=>$this->UploadeImage('identification_paper',$identification_paper)
                        ]);
                    }
                }
//            $user->security_settings()->create([
//                'device_uuid'=>$request->device_uuid,
//            ]);
                SendMailJob::dispatch($user);
                DB::commit();
                return $this->returnSuccessMessage('A verification code has been sent to your email, please see Gmail.');
            }
            if ($user_o->exists()&&$user_o->count()==1) {
                $user_o->first()->update([
                    'first_name'=>$request->first_name,
                    'last_name'=>$request->last_name,
                    'email'=>$request->email,
                    'password'=>bcrypt($request->password),
                    'image_url'=>$request->hasFile('image_profile')?
                        $this->UploadeImage('Profiles',$request->image_profile):
                        'default_avatar.png',
                    'region_id'=>$request->region_id,
                    'secondary_address'=>$request->secondary_address,
                    'phone_number'=>$request->phone_number,
                ]);
                $user=$user_o;
                if (Str::lower($request->user_type)===Str::lower('User')) {
                    $user->roles()->sync(2);
                }
                if (Str::lower($request->user_type)===Str::lower('Merchant')) {
                    $user->roles()->sync(3);
                    $identification_papers=$request->identification_papers;
                    foreach ($identification_papers as $identification_paper) {
                        $user->personal_identification_papers()->create([
                            'url'=>$this->UploadeImage('identification_paper',$identification_paper)
                        ]);
                    }
                }
//            $user->security_settings()->create([
//                'device_uuid'=>$request->device_uuid,
//            ]);
                SendMailJob::dispatch($user);
                DB::commit();
                return $this->returnSuccessMessage('A verification code has been sent to your email, please see Gmail.');
            }
            $user=User::create([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
                'image_url'=>$request->hasFile('image_profile')?
                    $this->UploadeImage('Profiles',$request->image_profile):
                    'default_avatar.png',
                'region_id'=>$request->region_id,
                'secondary_address'=>$request->secondary_address,
                'phone_number'=>$request->phone_number,
            ]);
            if (Str::lower($request->user_type)===Str::lower('User')) {
                $user->roles()->sync(2);
            }
            if (Str::lower($request->user_type)===Str::lower('Merchant')) {
                $user->roles()->sync(3);
                $identification_papers=$request->identification_papers;
                foreach ($identification_papers as $identification_paper) {
                    $user->personal_identification_papers()->create([
                        'url'=>$this->UploadeImage('identification_paper',$identification_paper)
                    ]);
                }
            }
//            $user->security_settings()->create([
//                'device_uuid'=>$request->device_uuid,
//            ]);
            SendMailJob::dispatch($user);
            DB::commit();
            return $this->returnSuccessMessage('A verification code has been sent to your email, please see Gmail.');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }



    public function login($request)
    {
        // TODO: Implement login() method.
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $user = User::where('email', $request['email'])
            ->where('verify',true)
            ->with(['roles','permissions','region'])->first();
        if (!$user) {
            return $this->returnError(403,'Your account has been not confirmed');
        }
//        if (Str::lower($user->roles()->first()->title)===Str::lower('Merchant')){
//            if ($user->merchant_register_order->status==='pending'){
//                return $this->returnError(403,'Your membership request has not been processed yet.');
//            }
//            if ($user->merchant_register_order->status==='unacceptable'){
//                return $this->returnError(403,'Your membership application has not been accepted. Please check with the administration or your notifications to find out why.');
//            }
//        }
        if (auth()->attempt($data)) {
            if (!$user->device_tokens()->where('token', $request['device_token'])->exists()) {
                $user->device_tokens()->create([
                    'token' => $request->device_token,
                ]);
                $user->save();
            }
            $token = auth()->user()->createToken('Signature-Group')->accessToken;

            return $this->returnData('user', collect(new UserResource($user))->put('token', $token), 'login has been successfully');
        }
        return $this->returnError(401, 'The login information is incorrect, please try again');

    }

    public function userInfo()
    {
        // TODO: Implement userInfo() method.

        $user = Auth::user();
        if ($user) {
            return $this->returnData('User', new UserResource($user->load(['region'])), 'user info successfully');
        }
        return $this->returnError('', 'You are not authorized to enter');
    }


    public function logout($request)
    {
        // TODO: Implement logout() method.

        $device_token=$request->device_token;
        $user_update=Auth::user();
        $user_update->device_tokens()->where('token', $device_token)->delete();
        $user = Auth::user()->token();
        $user->revoke();
        return $this->returnSuccessMessage('logged out successfully');
    }


    public function verify($request)
    {
        $user_o=User::where('email',$request->email)->where('verify',false);
        if ($user_o->exists()){
            $user = User::where('email', $request->email)
                ->where('verify',false)
                ->with(['roles','permissions','region'])
                ->first();
            if(!$user->verify)
            {
                if ($user->code == $request->code && Carbon::now()<$user->email_verified_at) {
                    // $user->update([
                    //     'code' => null,
                    //     'verify' => true
                    // ]);
                    $user->code=null;
                    $user->verify=true;
                    $user->save();
                    if (!$user->device_tokens()->where('token', $request['device_token'])->exists()) {

                        $user->device_tokens()->create([
                            'token' => $request->device_token,
                        ]);
                        $user->save();
                    }
                    if (Str::lower($user->roles()->first()->title)===Str::lower('Merchant')){
                        $merchant_register_order=$user->merchant_register_order()->create([
                            'serial_number'=>$this->generate_serialnumber(MerchantRegisterOrder::class),
                            'date'=>Carbon::now()->toDate(),
                        ]);

                        //send notifications to super admin and admin
                        $title = trans('notifications.send_merchant_register_order');
                        $body =trans('notifications.send_merchant_register_order_body')." : ".$merchant_register_order->serial_number;
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
                        $token = $user->createToken('Signature-Group')->accessToken;
                        return $this->returnData('user',collect(new UserResource($user))->put('token', $token), 'Your account has been successfully confirmed');
                    }
                    $token = $user->createToken('Signature-Group')->accessToken;
                    return $this->returnData('user', collect(new UserResource($user))->put('token', $token), 'Your account has been successfully confirmed');

                }
                return $this->returnError('0','Otp wrong');
            }
            return $this->returnError(0,'Your account has already been confirmed');
        }
        return $this->returnError(0,'Your account has already been confirmed');
    }


    public function resend_verify($request)
    {
        $user = User::where('email', $request->email)->first();
        if(!$user->verify)
        {
            SendMailJob::dispatch($user);
            return $this->returnSuccessMessage('A verification code has been Resent to your email, please see Gmail.');
        }
        return $this->returnError('0','Your account has already been confirmed');
    }


    public function forget_Password($request)
    {
        $user = User::where('email', $request->email)->first();
        ResetPasswordJob::dispatch($user);
        return $this->returnSuccessMessage('A verification code for Change Password has been sent to your email, please see Gmail.');
    }

    public function verify_reset_password($request)
    {
        $user = User::where('email', $request->email)->first();
        if($user->verify)
        {
            if ($user->code == $request->code && Carbon::now()<$user->email_verified_at) {
                $user->update([
                    'code' => null,
                    'password' => bcrypt($request->new_password)
                ]);

                return $this->returnSuccessMessage('Your Password account has been successfully Changed');
            }
            return $this->returnError(002,'Otp wrong');
        }
        return $this->returnError(0,'Your account has  been Unconfirmed');
    }

    public function verify_reset_password_b($request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user->verify) {
            if ($user->code == $request->code && Carbon::now() < $user->email_verified_at) {
                $user->verify = true;
                $user->save();
                return $this->returnSuccessMessage('Successfully');
            }
            return $this->returnError(002, 'Otp wrong');
        }
        return $this->returnError(0, 'Your account has  been Unconfirmed');
    }

    public function resend_verify_reset_password($request)
    {
        $user = User::where('email', $request->email)->first();
        if(!$user->verify)
        {
            ResetPasswordJob::dispatch($user);
            return $this->returnSuccessMessage('A verification code for Change Password has been Resent to your email, please see Gmail.');
        }
        return $this->returnError(0,'Your account has  been Unconfirmed');
    }


    public function verify_update_email($request)
    {
        $user=Auth::user();
        $code=$request->code;
        $email=$request->email;
        if ($user->code == $code && Carbon::now()<$user->email_verified_at)
        {
            $user->update([
                'email' => $email
            ]);
            return $this->returnSuccessMessage('تم تحديث الإيميل بنجاح');
        }
        return $this->returnError(00,'هناك مشكلة في الكود!!!!');
    }

}
