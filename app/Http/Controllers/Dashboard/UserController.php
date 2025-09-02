<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\User\AdminUserRequest;
use App\Repositories\Dashboard\User\AdminUserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected AdminUserRepositoryInterface $user;

    /**
     * @param AdminUserRepositoryInterface $user
     */
    public function __construct(AdminUserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    public function update_my_info(AdminUserRequest $request)
    {
        return $this->user->update_my_info($request);
    }
    public function get_my_notifications()
    {
        return $this->user->get_my_notifications();
    }
    public function get_user_profile(AdminUserRequest $request)
    {
        return $this->user->get_user_profile($request);
    }


}
