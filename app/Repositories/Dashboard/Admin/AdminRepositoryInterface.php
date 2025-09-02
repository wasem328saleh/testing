<?php

namespace App\Repositories\Dashboard\Admin;

interface AdminRepositoryInterface
{
    public function get_all_users();
    public function add_user($request);
    public function edit_user($request);
    public function delete_user($request);
    public function change_status_user($request);
    public function get_all_archived_users();
    public function remove_from_archived($request);
    public function unarchived_from_archived($request);
    public function get_all_system_contact_information();
    public function add_system_contact_information($request);
    public function edit_system_contact_information($request);
    public function delete_system_contact_information($request);
    public function send_notification($request);
}
