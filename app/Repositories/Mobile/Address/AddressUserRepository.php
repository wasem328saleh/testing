<?php

namespace App\Repositories\Mobile\Address;

use App\Http\Resources\CountryResource;
use App\Http\Resources\IdNameResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Traits\GeneralTrait;

class AddressUserRepository implements AddressUserRepositoryInterface
{
    use GeneralTrait;
    public function get_all_countries()
    {
        // TODO: Implement get_all_countries() method.
        try {
            $countries=Country::with('translation')->get();
            return $this->returnData('countries',IdNameResource::collection($countries));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_all_cities_by_country_id($country_id)
    {
        // TODO: Implement get_all_cities_by_country_id() method.
        try {
            $cities=City::where('country_id',$country_id)->with('translation')->get();
            return $this->returnData('cities',IdNameResource::collection($cities));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_all_regions_by_city_id($city_id)
    {
        // TODO: Implement get_all_regions_by_city_id() method.
        try {
            $regions=Region::where('city_id',$city_id)->with('translation')->get();
            return $this->returnData('regions',IdNameResource::collection($regions));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_all_address_location()
    {
        // TODO: Implement get_all_address_location() method.
        try {
            $countries=Country::with(['cities.regions','translation'])->get();
            return $this->returnData('countries',CountryResource::collection($countries));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }
}
