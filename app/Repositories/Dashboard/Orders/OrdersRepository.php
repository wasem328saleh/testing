<?php

namespace App\Repositories\Dashboard\Orders;

use App\Http\Resources\OrderResource;
use App\Models\Classification;
use App\Models\Order;
use App\Models\Role;
use App\Repositories\Dashboard\Orders\OrdersRepositoryInterface;
use App\Repositories\Dashboard\Properties\PropertiesManagementRepositoryInterface;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderCollection;

class OrdersRepository implements OrdersRepositoryInterface
{

    use GeneralTrait;
    public PropertiesManagementRepositoryInterface $properties;

    /**
     * @param PropertiesManagementRepositoryInterface $properties
     */
    public function __construct(PropertiesManagementRepositoryInterface $properties)
    {
        $this->properties = $properties;
    }

    public function get_all_orders($request)
    {
        // TODO: Implement get_all_orders() method.
        $name=request()->input('name');
        $publish_type=request()->input('publish_type');
        $status=request()->input('status');
                 $admin_id=Role::where('title','super_admin')->with('users')->first()->users()->first()->id;
        if (isset($status)) {
            // code...
                   $orders=Order::with(['orderable','user.personal_identification_papers'])
            ->whereHas('user',function ($q) use($name){
                $q->where(function($qq) use($name){
                    $qq->where('first_name','like','%'.$name.'%')
                        ->orWhere('last_name','like','%'.$name.'%');
                });
            })
            ->when(isset($publish_type),function ($q) use($publish_type){
                $q->whereHasMorph(
                    'orderable',
                    ['App\Models\Property'], // تحدید المودیلات المسموحة
                    function ($query) use($publish_type){
                        $query->where('publication_type', $publish_type);
                    }
                );
            })

            ->where('classification_id','!=',null)
            ->where('user_id','!=',$admin_id)
            ->where('status', $status)
//            ->whereIn('status',['pending', 'posted','cancelled'])
            ->paginate(10);

        return $this->returnData('orders',new OrderCollection($orders));
        }

        $orders=Order::with(['orderable','user.personal_identification_papers'])
            ->whereHas('user',function ($q) use($name){
                $q->where(function($qq) use($name){
                    $qq->where('first_name','like','%'.$name.'%')
                        ->orWhere('last_name','like','%'.$name.'%');
                });
            })
            ->when(isset($publish_type),function ($q) use($publish_type){
                $q->whereHasMorph(
                    'orderable',
                    ['App\Models\Property'], // تحدید المودیلات المسموحة
                    function ($query) use($publish_type){
                        $query->where('publication_type', $publish_type);
                    }
                );
            })

            ->where('classification_id','!=',null)
            ->where('user_id','!=',$admin_id)
            ->whereIn('status',['pending', 'posted','cancelled'])
            ->paginate(10);
        return $this->returnData('orders',new OrderCollection($orders));
    }

    public function get_all_ordersBy($request)
    {
        // TODO: Implement get_all_ordersBy() method.
        try {
            $classification_id=$request->classification_id;
            $admin_id=Role::where('title','super_admin')->with('users')->first()->users()->first()->id;
            $orders=OrderResource::collection(Order::where('classification_id',$classification_id)
                ->where('user_id','!=',$admin_id)
                ->where('status',"pending")
                ->with(['orderable','user.personal_identification_papers'])->get());
            return $this->returnData('orders', $orders);
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function accept_order($request)
    {
        // TODO: Implement accept_order() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    $action="accept_order";
                    $properties=$this->properties->change_property_status($action,$request);
                    return $properties;
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function cancel_order($request)
    {
        // TODO: Implement cancel_order() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    $action="cancel_order";
                    $properties=$this->properties->change_property_status($action,$request);
                    return $properties;
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
