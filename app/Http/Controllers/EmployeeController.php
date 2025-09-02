<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Repositories\Employee\EmployeeRepositoryInterface;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected EmployeeRepositoryInterface $Employee;

    /**
     * @param EmployeeRepositoryInterface $Employee
     */
    public function __construct(EmployeeRepositoryInterface $Employee)
    {
        $this->Employee = $Employee;
    }

    public function show_profile()
    {
        return $this->Employee->show_profile();
    }
    public function edit_profile(EmployeeRequest $request)
    {
        return $this->Employee->edit_profile($request);
    }
    public function get_all_my_tasks()
    {
        return $this->Employee->get_all_my_tasks();
    }
    public function add_task(EmployeeRequest $request)
    {
        return $this->Employee->add_task($request);
    }
    public function delete_task(EmployeeRequest $request)
    {
        return $this->Employee->delete_task($request);
    }


}
