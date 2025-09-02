<?php

namespace App\Repositories\Dashboard\Services;

interface ServicesManagementRepositoryInterface
{
    public function get_all_categories();
    public function get_all_categories_services();
    public function add_categories_services($request);
    public function update_categories_services($request);
    public function delete_categories_services($request);
    public function get_all_providers_by_service_id($id);
    public function add_providers_services($request);
    public function update_providers_services($request);
    public function delete_providers_services($request);
    public function get_all_archived_providers_services();
    public function remove_from_archived($request);
    public function unarchived_from_archived($request);
    public function get_all_orders_services();
    public function accept_order($request);
    public function cancel_order($request);
}
