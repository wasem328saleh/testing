<?php

namespace App\Repositories\Dashboard\Auth;

interface AuthManagementRepositoryInterface
{
    public function login($request);
    public function userInfo();
    public function logout($request);
    public function forget_Password($request);
    public function verify_reset_password($request);
    public function verify_reset_password_b($request);
    public function resend_verify_reset_password($request);
//    public function verify_update_email($request);

}
