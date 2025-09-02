<?php

namespace App\Repositories\Dashboard\Complaints;

interface ComplaintManagementRepositoryInterface
{
    public function get_all_complaints();
    public function add_complaint_response($request);
}
