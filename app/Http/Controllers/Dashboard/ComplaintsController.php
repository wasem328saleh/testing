<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Complaints\ComplaintRequest;
use App\Repositories\Dashboard\Complaints\ComplaintManagementRepositoryInterface;
use Illuminate\Http\Request;

class ComplaintsController extends Controller
{
    protected ComplaintManagementRepositoryInterface $complaint;

    /**
     * @param ComplaintManagementRepositoryInterface $complaint
     */
    public function __construct(ComplaintManagementRepositoryInterface $complaint)
    {
        $this->complaint = $complaint;
    }

    public function get_all_complaints(){
        return $this->complaint->get_all_complaints();
    }
    public function add_complaint_response(ComplaintRequest $request){
        return $this->complaint->add_complaint_response($request);
    }


}
