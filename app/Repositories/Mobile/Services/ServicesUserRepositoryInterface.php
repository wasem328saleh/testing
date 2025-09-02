<?php

namespace App\Repositories\Mobile\Services;

interface ServicesUserRepositoryInterface
{
    public function get_all_categories_services();
    public function get_all_categories_services_with_service_providers();
    public function get_service_providers_by_category_id($request);
    public function send_order_add_service_provider($request);
    public function get_my_orders_add_service_provider();
    public function edited_my_service_provider($request);
    public function deleted_my_service_provider($request);
    public function edited_activation_my_service_provider($request);
    public function rate_service_provider($request);



}
