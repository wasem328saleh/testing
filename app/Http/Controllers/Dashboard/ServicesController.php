<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Services\ServicesRequest;
use App\Repositories\Dashboard\Services\ServicesManagementRepositoryInterface;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    protected ServicesManagementRepositoryInterface $services;

    /**
     * @param ServicesManagementRepositoryInterface $services
     */
    public function __construct(ServicesManagementRepositoryInterface $services)
    {
        $this->services = $services;
    }

    public function get_all_categories(){
        return $this->services->get_all_categories();
    }
    public function get_all_categories_services(){
        return $this->services->get_all_categories_services();
    }
    public function add_categories_services(ServicesRequest $request){
        return $this->services->add_categories_services($request);
    }
    public function update_categories_services(ServicesRequest $request){
        return $this->services->update_categories_services($request);
    }
    public function delete_categories_services(ServicesRequest $request){
        return $this->services->delete_categories_services($request);
    }
    public function get_all_providers_by_service_id($id){
        return $this->services->get_all_providers_by_service_id($id);
    }
    public function add_providers_services(ServicesRequest $request){
        return $this->services->add_providers_services($request);
    }
    public function update_providers_services(ServicesRequest $request){
        return $this->services->update_providers_services($request);
    }
    public function delete_providers_services(ServicesRequest $request){
        return $this->services->delete_providers_services($request);
    }
    public function get_all_archived_providers_services(){
        return $this->services->get_all_archived_providers_services();
    }
    public function remove_from_archived(ServicesRequest $request){
        return $this->services->remove_from_archived($request);
    }
    public function unarchived_from_archived(ServicesRequest $request){
        return $this->services->unarchived_from_archived($request);
    }
    public function get_all_orders_services(){
        return $this->services->get_all_orders_services();
    }
    public function accept_order(ServicesRequest $request){
        return $this->services->accept_order($request);
    }
    public function cancel_order(ServicesRequest $request){
        return $this->services->cancel_order($request);
    }
}
