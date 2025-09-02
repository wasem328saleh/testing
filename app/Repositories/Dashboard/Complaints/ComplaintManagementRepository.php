<?php

namespace App\Repositories\Dashboard\Complaints;

use App\Http\Resources\ComplaintResource;
use App\Jobs\SendNotification;
use App\Models\Complaint;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class ComplaintManagementRepository implements ComplaintManagementRepositoryInterface
{
    use GeneralTrait;
    public function get_all_complaints()
    {
        // TODO: Implement get_all_complaints() method.
        try {
            $complaints=Complaint::all();
            $complaints=ComplaintResource::collection($complaints);
            return $this->returnData('complaints',$complaints);
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function add_complaint_response($request)
    {
        // TODO: Implement add_complaint_response() method.
        try {
            DB::beginTransaction();
            $complaint_id=request()->complaint_id;
            $complaint=Complaint::where('id',$complaint_id)->first();
            if (!$complaint){
                DB::rollBack();
                return $this->returnError(404,'complaints not found');
            }
            $reply='';
            if (request()->reply!=null)
            {
                $reply=request()->reply;
            }
            $images=[];
            if (request()->hasFile('complaint_image')){

                foreach ($request->file('complaint_image') as $image){
                    $images[]=$this->UploadeImage('complaint_image',$image);
                }
                $complaint->reply()->create([
                    'response_text'=>$reply,
                    'response_media'=>$images
                ]);
                DB::commit();
                return $this->returnSuccessMessage('complaint  Reply created successfully');
            }

            $complaint->reply()->create([
                'response_text'=>$reply
            ]);
            //send notifications
            $title = trans('notifications.add_complaint_response');
            $body =trans('notifications.add_complaint_response_body') . " : " . $complaint->serial_number." ".trans('notifications.add_complaint_response_body1');
            $type = 'info';
            $user=$complaint->user;

            $data=[
                'users'=>[$user],
                'title'=>$title,
                'body'=>$body,
                'type'=>$type
            ];
            dispatch(new SendNotification($data));
            DB::commit();
            return $this->returnSuccessMessage('complaint  Reply created successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }
}
