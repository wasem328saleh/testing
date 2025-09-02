<?php

namespace App\Repositories\Dashboard\Orders;

interface OrdersRepositoryInterface
{
    public function get_all_orders($request);
    public function get_all_ordersBy($request);
    public function accept_order($request);
    public function cancel_order($request);
}
