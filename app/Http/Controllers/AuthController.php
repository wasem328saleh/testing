<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repositories\Auth\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthRepositoryInterface $Auth;

    /**
     * @param AuthRepositoryInterface $Auth
     */
    public function __construct(AuthRepositoryInterface $Auth)
    {
        $this->Auth = $Auth;
    }
    public function login(UserRequest $request){
        return $this->Auth->login($request);
    }
    public function userInfo(){
        return $this->Auth->userInfo();
    }

    public function logout(){
        return $this->Auth->logout();
    }

}
