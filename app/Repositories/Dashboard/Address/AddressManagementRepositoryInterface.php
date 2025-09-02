<?php

namespace App\Repositories\Dashboard\Address;

interface AddressManagementRepositoryInterface
{
    public function get_all_countries();
    public function add_country($request);
    public function update_country($request);
    public function delete_country($request);
    public function get_all_cities();
    public function add_city($request);
    public function update_city($request);
    public function delete_city($request);
    public function get_all_regions();
    public function add_region($request);
    public function update_region($request);
    public function delete_region($request);
    public function get_all_cities_by_country_id($country_id);
    public function get_all_regions_by_city_id($city_id);
    public function get_all_address();
}
