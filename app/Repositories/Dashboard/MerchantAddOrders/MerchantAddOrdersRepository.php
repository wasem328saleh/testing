<?php

namespace App\Repositories\Dashboard\MerchantAddOrders;

use App\Http\Resources\MerchantRegisterOrderCollection;
use App\Jobs\SendNotification;
use App\Models\MerchantRegisterOrder;
use App\Models\Property;
use App\Repositories\Dashboard\MerchantAddOrders\MerchantAddOrdersRepositoryInterface;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class MerchantAddOrdersRepository implements MerchantAddOrdersRepositoryInterface
{

    use GeneralTrait;
    public function get_all_orders($request)
    {
        // TODO: Implement get_all_orders() method.

        try {
            $name=request()->input('name');
            $status=request()->input('status');

            if (isset($status)){
                $orders=MerchantRegisterOrder::with('user.personal_identification_papers')
                    ->whereHas('user',function ($q) use($name){
                        $q->where(function($qq) use($name){
                            $qq->where('first_name','like','%'.$name.'%')
                                ->orWhere('last_name','like','%'.$name.'%');
                        });
                    })
                    ->where('status', $status)
                    ->paginate(10);
                return $this->returnData('orders',new MerchantRegisterOrderCollection($orders));
            }
            if (isset($name)){
                $orders=MerchantRegisterOrder::with('user.personal_identification_papers')
                    ->whereHas('user',function ($q) use($name){
                        $q->where(function($qq) use($name){
                            $qq->where('first_name','like','%'.$name.'%')
                                ->orWhere('last_name','like','%'.$name.'%');
                        });
                    })
                    ->whereIn('status',['pending', 'accept','unacceptable'])
                    ->paginate(10);
                return $this->returnData('orders',new MerchantRegisterOrderCollection($orders));
            }
            $orders=MerchantRegisterOrder::with('user.personal_identification_papers')
                ->whereIn('status',['pending', 'accept','unacceptable'])
                ->paginate(10);

            return $this->returnData('orders',new MerchantRegisterOrderCollection($orders));
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function accept_order($request)
    {
        // TODO: Implement accept_order() method.

        try {
            DB::beginTransaction();

                $order_id=$request->order_id;
                $order=MerchantRegisterOrder::with('user.device_tokens')->where('id',$order_id)->first();
                if ($order->status=="pending"){
                    $order->status="accept";
                    $order->save();

                    //send notifications
                    $title = trans('notifications.accept_order_merchant');
                    $body =trans('notifications.accept_order_merchant_body');
                    $type = 'success';
                    $user=$order->user;

                    $data=[
                        'users'=>[$user],
                        'title'=>$title,
                        'body'=>$body,
                        'type'=>$type
                    ];
                    dispatch(new SendNotification($data));
                }else{
                    DB::rollBack();
                    return $this->returnError(500,"This order is Already Active");
                }
                DB::commit();
                return $this->returnSuccessMessage("Accepted");
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function cancel_order($request)
    {
        // TODO: Implement cancel_order() method.

        try {
            DB::beginTransaction();
            $order_id=$request->order_id;
            $reply=$request->reply;
            $order=MerchantRegisterOrder::with('user.device_tokens')->where('id',$order_id)->first();



            if ($order->status=="pending"){
                $order->status="unacceptable";
                $order->reply=$reply;
                $order->save();

                //send notifications
                $title = trans('notifications.cancel_order_merchant');
                $body =trans('notifications.cancel_order_merchant_body1') ."\n".trans('notifications.cancel_order_merchant_body2')." : ".$reply;
                $type = 'error';
                $user=$order->user;
                $data=[
                    'users'=>[$user],
                    'title'=>$title,
                    'body'=>$body,
                    'type'=>$type
                ];
                dispatch(new SendNotification($data));
        }else{
        DB::rollBack();
        return $this->returnError(500,"This order is Already Active");
    }
            DB::commit();
            return $this->returnSuccessMessage("Cancelled");
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
