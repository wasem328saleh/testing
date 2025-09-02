<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Address\AddressRequest;
use App\Repositories\Dashboard\Address\AddressManagementRepositoryInterface;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    protected AddressManagementRepositoryInterface $address;

    /**
     * @param $address
     */
    public function __construct(AddressManagementRepositoryInterface $address)
    {
        $this->address = $address;
    }

    public function get_all_countries(){
        return $this->address->get_all_countries();
    }
    public function add_country(AddressRequest $request){
        return $this->address->add_country($request);
    }
    public function update_country(AddressRequest $request){
        return $this->address->update_country($request);
    }
    public function delete_country(AddressRequest $request){
        return $this->address->delete_country($request);
    }
    public function get_all_cities(){
        return $this->address->get_all_cities();
    }
    public function add_city(AddressRequest $request){
        return $this->address->add_city($request);
    }
    public function update_city(AddressRequest $request){
        return $this->address->update_city($request);
    }
    public function delete_city(AddressRequest $request){
        return $this->address->delete_city($request);
    }
    public function get_all_regions(){
        return $this->address->get_all_regions();
    }
    public function add_region(AddressRequest $request){
        return $this->address->add_region($request);
    }
    public function update_region(AddressRequest $request){
        return $this->address->update_region($request);
    }
    public function delete_region(AddressRequest $request){
        return $this->address->delete_region($request);
    }
    public function get_all_cities_by_country_id($country_id){
        return $this->address->get_all_cities_by_country_id($country_id);
    }
    public function get_all_regions_by_city_id($city_id){
        return $this->address->get_all_regions_by_city_id($city_id);
    }
    public function get_all_address(){
        return $this->address->get_all_address();
    }
}
