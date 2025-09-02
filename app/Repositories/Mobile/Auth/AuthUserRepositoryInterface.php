<?php

namespace App\Repositories\Mobile\Auth;

interface AuthUserRepositoryInterface
{
    public function register($request);
    public function login($request);
    public function userInfo();
    public function logout($request);
    public function verify($request);
    public function resend_verify($request);

    public function forget_Password($request);

    public function verify_reset_password($request);
    public function verify_reset_password_b($request);


    public function resend_verify_reset_password($request);

    public function verify_update_email($request);

}
