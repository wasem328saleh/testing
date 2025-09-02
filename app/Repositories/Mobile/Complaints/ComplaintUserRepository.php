<?php

namespace App\Repositories\Mobile\Complaints;

use App\Http\Resources\ComplaintResource;
use App\Jobs\SendNotification;
use App\Models\Complaint;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComplaintUserRepository implements ComplaintUserRepositoryInterface
{
    use GeneralTrait;
    public function send_complaint($request)
    {
        // TODO: Implement send_complaint() method.
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $title=request()->title;
            $text=request()->text;
            $complaint=new Complaint();
            if (request()->hasFile('medias')){
                $medias=[];
                $files=request()->medias;
                foreach ($files as $file){
                    $medias[]=$this->UploadeImage('complaints',$file);
                }
                $complaint->complaint_media=$medias;
            }
            $complaint->serial_number=$this->generate_serialnumber(Complaint::class);
            $complaint->title=$title;
            $complaint->complaint_text=$text;
            $complaint->user_id=$user->id;
            $complaint->save();
            //send notifications to super admin and admin
            $title = trans('notifications.send_complaint')." : ".$complaint->title;
            $body =$complaint->complaint_text;
            $type = 'info';
            $users=User::with('device_tokens')
                ->whereHas('roles',function($q){
                    $q->where(function ($query){
                        $query->where('title','super_admin')
                            ->orWhere('title','admin');
                    });
                })->get();

            $data=[
                'users'=>[$users],
                'title'=>$title,
                'body'=>$body,
                'type'=>$type
            ];
            dispatch(new SendNotification($data));
            DB::commit();
            return $this->returnSuccessMessage('Done !!');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function get_my_complaints()
    {
        // TODO: Implement get_my_complaints() method.
        try {
            $user=Auth::user();
            $my_complaints=$user->complaints()->with('reply')->get();
            $my_complaints=ComplaintResource::collection($my_complaints);
            return $this->returnData('complaints',$my_complaints);
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function rate_complaint_response($request)
    {
        // TODO: Implement rate_complaint_response() method.
    }
}
