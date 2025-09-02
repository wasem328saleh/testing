<?php

namespace App\Repositories\Dashboard\User;

interface AdminUserRepositoryInterface
{
    public function update_my_info($request);
    public function get_my_notifications();
    public function get_user_profile($request);
}
