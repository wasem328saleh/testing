<?php

namespace App\Repositories\Mobile\User;

interface UserRepositoryInterface
{
//    public function add_interests($request);
//    public function change_interests($request);
    public function get_my_notifications();
    public function get_my_notifications_by_device_token($request);
    public function read_notifications();
    public function get_system_contact_information();
    public function update_my_info($request);
    public function add_to_favourites($request);
    public function get_my_favourites();
    public function delete_from_my_favourites($request);
//    public function locked_application($request);
//    public function check_password_locked_application($request);
    public function delete_my_account();
    public function get_user_profile($request);
//    public function rate_user($request);
}
