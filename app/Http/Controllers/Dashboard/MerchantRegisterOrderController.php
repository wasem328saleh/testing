<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\MerchantRegisterOrderRequest;
use App\Repositories\Dashboard\MerchantAddOrders\MerchantAddOrdersRepositoryInterface;
use Illuminate\Http\Request;

class MerchantRegisterOrderController extends Controller
{
    protected MerchantAddOrdersRepositoryInterface $orders;

    /**
     * @param MerchantAddOrdersRepositoryInterface $orders
     */
    public function __construct(MerchantAddOrdersRepositoryInterface $orders)
    {
        $this->orders = $orders;
    }

    public function get_all_orders(MerchantRegisterOrderRequest $request)
    {
        return $this->orders->get_all_orders($request);
    }
    public function accept_order(MerchantRegisterOrderRequest $request)
    {
        return $this->orders->accept_order($request);
    }
    public function cancel_order(MerchantRegisterOrderRequest $request)
    {
        return $this->orders->cancel_order($request);
    }
}
