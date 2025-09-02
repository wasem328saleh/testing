<?php

namespace App\Repositories\Mobile\Complaints;

interface ComplaintUserRepositoryInterface
{
    public function send_complaint($request);
    public function get_my_complaints();
    public function rate_complaint_response($request);
}
