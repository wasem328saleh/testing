<?php

namespace App\Rules;

use App\Models\Order;
use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class serviceorderrule implements Rule
{
    use GeneralTrait;

    protected $order_id;

    /**
     * RelationshipExists constructor.
     * @param $rent_type
     * @param $publication_type
     */
    public function __construct($order_id)
    {
        $this->order_id=$order_id;
    }


    public function passes($attribute, $value)
    {
        $order=Order::where('id',$this->order_id)->first();

        return $order->for_service_provider;
    }

    public function message()
    {

        return 'يجب ان يكون الطلب هو لاضافة مقدم خدمة';
    }
}
