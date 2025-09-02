<?php

namespace App\Repositories\Dashboard\SuperAdmin;

interface SuperAdminRepositoryInterface
{
    public function add_admin($request);
    public function add_user($request);
    public function change_status_user($request);
    public function change_role_user($request);
    public function get_all_admins();
    public function get_all_users();
    public function edit_user($request);
    public function delete_user($request);
    public function get_all_logs_admin($request);
    public function get_all_archived_admins();
    public function get_all_archived_users();
    public function remove_from_archived($request);
    public function unarchived_from_archived($request);
    public function get_all_roles();
    public function get_all_permissions();
    public function edit_permissions_role($request);
    public function send_notification($request);
    public function get_all_system_contact_information();
    public function add_system_contact_information($request);
    public function edit_system_contact_information($request);
    public function delete_system_contact_information($request);

}
