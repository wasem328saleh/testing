<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Complaint\ComplaintRequest;
use App\Repositories\Mobile\Complaints\ComplaintUserRepositoryInterface;
use Illuminate\Http\Request;

class ComplaintsController extends Controller
{
    protected ComplaintUserRepositoryInterface $complaint;

    /**
     * @param ComplaintUserRepositoryInterface $complaint
     */
    public function __construct(ComplaintUserRepositoryInterface $complaint)
    {
        $this->complaint = $complaint;
    }

    public function send_complaint(ComplaintRequest $request)
    {
        return $this->complaint->send_complaint($request);
    }
    public function get_my_complaints()
    {
        return $this->complaint->get_my_complaints();
    }
}
