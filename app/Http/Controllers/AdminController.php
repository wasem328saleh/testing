<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Repositories\Admin\AdminRepositoryInterface;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminRepositoryInterface $Admin;

    /**
     * @param AdminRepositoryInterface $Admin
     */
    public function __construct(AdminRepositoryInterface $Admin)
    {
        $this->Admin = $Admin;
    }

    public function forget_Password()
    {
        return $this->Admin->forget_Password();
    }
    public function add_employee(AdminRequest $request)
    {
        return $this->Admin->add_employee($request);
    }
    public function delete_employee(AdminRequest $request)
    {
        return $this->Admin->delete_employee($request);
    }
    public function edit_employee(AdminRequest $request)
    {
        return $this->Admin->edit_employee($request);
    }
    public function get_all_employee()
    {
        return $this->Admin->get_all_employee();
    }
    public function show_employee(AdminRequest $request)
    {
        return $this->Admin->show_employee($request);
    }
    public function get_all_tasks_employee(AdminRequest $request)
    {
        return $this->Admin->get_all_tasks_employee($request);
    }
    public function active_or_not_employee(AdminRequest $request)
    {
        return $this->Admin->active_or_not_employee($request);
    }
    public function payment_to_employee(AdminRequest $request)
    {
        return $this->Admin->payment_to_employee($request);
    }
    public function edit_cost_per_hour(AdminRequest $request)
    {
        return $this->Admin->edit_cost_per_hour($request);
    }

    public function get_cost_per_hour()
    {
        return $this->Admin->get_cost_per_hour();
    }
}
