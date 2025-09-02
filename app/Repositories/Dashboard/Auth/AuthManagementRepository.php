<?php

namespace App\Repositories\Dashboard\Auth;

use App\Http\Resources\UserResource;
use App\Jobs\ResetPasswordAdminJob;
use App\Jobs\ResetPasswordJob;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthManagementRepository implements AuthManagementRepositoryInterface
{
    use GeneralTrait;


    public function login($request)
    {
        // TODO: Implement login() method.
        $data = [
        'email' => $request->email,
        'password' => $request->password
        ];
        $user = User::where('email', $request['email'])->with(['roles','permissions','region'])->first();
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
                $user->update([
                'password' => bcrypt($request->new_password)
                ]);
                return $this->returnSuccessMessage('Your Password account has been successfully Changed');
        }
        return $this->returnError(0,'Your account has  been Unconfirmed');
    }

    public function verify_reset_password_b($request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user->verify) {
            if ($user->code == $request->code && Carbon::now() < $user->email_verified_at) {
                $user->code = null;
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
        return $this->returnError(0,'Your account has  been Unconfirmed');    }


//    public function verify_update_email($request)
//    {
//        $user=Auth::user();
//        $code=$request->code;
//        $email=$request->email;
//        if ($user->code == $request->code && Carbon::now()<$user->email_verified_at)
//        {
//            $user->update([
//                'email' => $email
//            ]);
//            return $this->returnSuccessMessage('تم تحديث الإيميل بنجاح');
//        }
//        return $this->returnError(00,'هناك مشكلة في الكود!!!!');
//    }
}
