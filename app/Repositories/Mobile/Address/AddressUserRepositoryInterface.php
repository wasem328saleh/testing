<?php

namespace App\Repositories\Mobile\Address;

interface AddressUserRepositoryInterface
{
    public function get_all_countries();
    public function get_all_cities_by_country_id($country_id);
    public function get_all_regions_by_city_id($city_id);
    public function get_all_address_location();
}
