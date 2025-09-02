<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Repositories\Mobile\Address\AddressUserRepositoryInterface;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected AddressUserRepositoryInterface $address;

    /**
     * @param AddressUserRepositoryInterface $address
     */
    public function __construct(AddressUserRepositoryInterface $address)
    {
        $this->address = $address;
    }

    public function get_all_countries()
    {
        return $this->address->get_all_countries();
    }
    public function get_all_cities_by_country_id($country_id)
    {
        return $this->address->get_all_cities_by_country_id($country_id);
    }
    public function get_all_regions_by_city_id($city_id)
    {
        return $this->address->get_all_regions_by_city_id($city_id);
    }
    public function get_all_address_location()
    {
        return $this->address->get_all_address_location();
    }
}
