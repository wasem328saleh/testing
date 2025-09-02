<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Auth\AuthManagementRequest;
use App\Repositories\Dashboard\Auth\AuthManagementRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthManagementRepositoryInterface $auth;

    /**
     * @param AuthManagementRepositoryInterface $auth
     */
    public function __construct(AuthManagementRepositoryInterface $auth)
    {
        $this->auth = $auth;
    }

    public function login(AuthManagementRequest $request)
    {
        return $this->auth->login($request);
    }
    public function userInfo()
    {
        return $this->auth->userInfo();
    }
    public function logout(AuthManagementRequest $request)
    {
        return $this->auth->logout($request);
    }
    public function forget_Password(AuthManagementRequest $request){
        return $this->auth->forget_Password($request);
    }

    public function verify_reset_password(AuthManagementRequest $request){
        return $this->auth->verify_reset_password($request);
    }
    public function verify_reset_password_b(AuthManagementRequest $request){
        return $this->auth->verify_reset_password_b($request);
    }


    public function resend_verify_reset_password(AuthManagementRequest $request){
        return $this->auth->resend_verify_reset_password($request);
    }
}
