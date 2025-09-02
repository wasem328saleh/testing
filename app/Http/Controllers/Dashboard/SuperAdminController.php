<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SuperAdmin\SuperAdminRequest;
use App\Repositories\Dashboard\SuperAdmin\SuperAdminRepositoryInterface;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    protected SuperAdminRepositoryInterface $superAdmin;

    /**
     * @param SuperAdminRepositoryInterface $superAdmin
     */
    public function __construct(SuperAdminRepositoryInterface $superAdmin)
    {
        $this->superAdmin = $superAdmin;
    }
    public function get_all_admins(){
        return $this->superAdmin->get_all_admins();
    }
    public function add_admin(SuperAdminRequest $request){
        return $this->superAdmin->add_admin($request);
    }
    public function change_status_user(SuperAdminRequest $request){
        return $this->superAdmin->change_status_user($request);
    }
    public function change_role_user(SuperAdminRequest $request){
        return $this->superAdmin->change_role_user($request);
    }

    public function get_all_users(){
        return $this->superAdmin->get_all_users();
    }

    public function add_user(SuperAdminRequest $request){
        return $this->superAdmin->add_user($request);
    }
    public function edit_user(SuperAdminRequest $request){
        return $this->superAdmin->edit_user($request);
    }
    public function delete_user(SuperAdminRequest $request){
        return $this->superAdmin->delete_user($request);
    }
    public function get_all_logs_admin(SuperAdminRequest $request){
        return $this->superAdmin->get_all_logs_admin($request);
    }
    public function get_all_roles(){
        return $this->superAdmin->get_all_roles();
    }
    public function get_all_permissions(){
        return $this->superAdmin->get_all_permissions();
    }
    public function edit_permissions_role(SuperAdminRequest $request){
        return $this->superAdmin->edit_permissions_role($request);
    }
    public function send_notification(SuperAdminRequest $request){
        return $this->superAdmin->send_notification($request);
    }
    public function get_all_system_contact_information(){
        return $this->superAdmin->get_all_system_contact_information();
    }
    public function add_system_contact_information(SuperAdminRequest $request){
        return $this->superAdmin->add_system_contact_information($request);
    }
    public function edit_system_contact_information(SuperAdminRequest $request){
        return $this->superAdmin->edit_system_contact_information($request);
    }
    public function delete_system_contact_information(SuperAdminRequest $request){
        return $this->superAdmin->delete_system_contact_information($request);
    }
}
