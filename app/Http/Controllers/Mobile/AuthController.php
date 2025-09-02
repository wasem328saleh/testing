<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Auth\AuthUserRequest;
use App\Http\Requests\UserRequest;
use App\Repositories\Mobile\Auth\AuthUserRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthUserRepositoryInterface $auth;

    /**
     * @param AuthUserRepositoryInterface $auth
     */
    public function __construct(AuthUserRepositoryInterface $auth)
    {
        $this->auth = $auth;
    }

    public function register(AuthUserRequest $request)
    {
        return $this->auth->register($request);
    }

    public function login(AuthUserRequest $request){
        return $this->auth->login($request);
    }

    public function userInfo()
    {
     return $this->auth->userInfo();
    }

    public function logout(AuthUserRequest $request)
    {
return $this->auth->logout($request);
    }

    public function verify(AuthUserRequest $request){
        return $this->auth->verify($request);
    }
    public function resend_verify(AuthUserRequest $request){
        return $this->auth->resend_verify($request);
    }

    public function forget_Password(AuthUserRequest $request){
        return $this->auth->forget_Password($request);
    }

    public function verify_reset_password(AuthUserRequest $request){
        return $this->auth->verify_reset_password($request);
    }
    public function verify_reset_password_b(AuthUserRequest $request){
        return $this->auth->verify_reset_password_b($request);
    }


    public function resend_verify_reset_password(AuthUserRequest $request){
        return $this->auth->resend_verify_reset_password($request);
    }

    public function verify_update_email(AuthUserRequest $request){
        return $this->auth->verify_update_email($request);
    }


}
