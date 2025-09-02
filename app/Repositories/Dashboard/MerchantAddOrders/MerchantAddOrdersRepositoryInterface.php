<?php

namespace App\Repositories\Dashboard\MerchantAddOrders;

interface MerchantAddOrdersRepositoryInterface
{
    public function get_all_orders($request);
    public function accept_order($request);
    public function cancel_order($request);
}
