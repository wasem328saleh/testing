<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Services\ServicesRequest;
use App\Repositories\Mobile\Services\ServicesUserRepositoryInterface;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    protected ServicesUserRepositoryInterface $services;

    /**
     * @param ServicesUserRepositoryInterface $services
     */
    public function __construct(ServicesUserRepositoryInterface $services)
    {
        $this->services = $services;
    }

    public function get_all_categories_services()
    {
        return $this->services->get_all_categories_services();
    }
    public function get_all_categories_services_with_service_providers()
    {
        return $this->services->get_all_categories_services_with_service_providers();
    }
    public function get_service_providers_by_category_id(ServicesRequest $request)
    {
        return $this->services->get_service_providers_by_category_id($request);
    }
    public function send_order_add_service_provider(ServicesRequest $request)
    {
        return $this->services->send_order_add_service_provider($request);
    }
    public function get_my_orders_add_service_provider()
    {
        return $this->services->get_my_orders_add_service_provider();
    }
    public function edited_my_service_provider(ServicesRequest $request)
    {
        return $this->services->edited_my_service_provider($request);
    }
    public function deleted_my_service_provider(ServicesRequest $request)
    {
        return $this->services->deleted_my_service_provider($request);
    }
    public function edited_activation_my_service_provider(ServicesRequest $request)
    {
        return $this->services->edited_activation_my_service_provider($request);
    }
    public function rate_service_provider(ServicesRequest $request)
    {
        return $this->services->rate_service_provider($request);
    }
}
