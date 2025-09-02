<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\AdminRequest;
use App\Repositories\Dashboard\Admin\AdminRepositoryInterface;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminRepositoryInterface $admin;

    /**
     * @param $admin
     */
    public function __construct(AdminRepositoryInterface $admin)
    {
        $this->admin = $admin;
    }

    public function get_all_users(){
        return $this->admin->get_all_users();
    }
    public function add_user(AdminRequest $request){
        return $this->admin->add_user($request);
    }
    public function edit_user(AdminRequest $request){
        return $this->admin->edit_user($request);
    }
    public function delete_user(AdminRequest $request){
        return $this->admin->delete_user($request);
    }
    public function change_status_user(AdminRequest $request){
        return $this->admin->change_status_user($request);
    }
    public function get_all_archived_users(){
        return $this->admin->get_all_archived_users();
    }
    public function remove_from_archived(AdminRequest $request){
        return $this->admin->remove_from_archived($request);
    }
    public function unarchived_from_archived(AdminRequest $request){
        return $this->admin->unarchived_from_archived($request);
    }
    public function get_all_system_contact_information(){
        return $this->admin->get_all_system_contact_information();
    }
    public function add_system_contact_information(AdminRequest $request){
        return $this->admin->add_system_contact_information($request);
    }
    public function edit_system_contact_information(AdminRequest $request){
        return $this->admin->edit_system_contact_information($request);
    }
    public function delete_system_contact_information(AdminRequest $request){
        return $this->admin->delete_system_contact_information($request);
    }
    public function send_notification(AdminRequest $request){
        return $this->admin->send_notification($request);
    }
}
