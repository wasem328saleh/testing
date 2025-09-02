<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\User\UserRequest;
use App\Repositories\Mobile\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserRepositoryInterface $user;

    /**
     * @param UserRepositoryInterface $user
     */
    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    public function get_my_notifications()
{
    return $this->user->get_my_notifications();
}
    public function read_notifications()
{
    return $this->user->read_notifications();
}
    public function get_system_contact_information()
{
    return $this->user->get_system_contact_information();
}
    public function update_my_info(UserRequest $request)
{
    return $this->user->update_my_info($request);
}
    public function add_to_favourites(UserRequest $request)
{
    return $this->user->add_to_favourites($request);
}
    public function get_my_favourites()
{
    return $this->user->get_my_favourites();
}
    public function delete_from_my_favourites(UserRequest $request)
{
    return $this->user->delete_from_my_favourites($request);
}

    public function delete_my_account()
{
    return $this->user->delete_my_account();
}
    public function get_user_profile(UserRequest $request)
{
    return $this->user->get_user_profile($request);
}

    public function get_my_notifications_by_device_token(UserRequest $request)
    {
        return $this->user->get_my_notifications_by_device_token($request);
    }
}
