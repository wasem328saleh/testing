<?php

namespace App\Repositories\Dashboard\Address;

use App\Http\Resources\CountryResource;
use App\Http\Resources\IdNameResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class AddressManagementRepository implements AddressManagementRepositoryInterface
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

    public function add_country($request)
    {
        // TODO: Implement add_country() method.
        try {
            DB::beginTransaction();
            $country_name=request()->country_name;
            Country::create([
                'name'=>$country_name
            ]);
            DB::commit();
            return $this->returnSuccessMessage('country added successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function update_country($request)
    {
        // TODO: Implement update_country() method.
        try {
            DB::beginTransaction();
            $country_id=request()->country_id;
            $country=Country::where('id',$country_id)->first();
            if (!$country) {
                DB::rollBack();
                return $this->returnError(404,'country not found');
            }
            $country_name=request()->country_name;
            $country->name=$country_name;
            $country->save();
            DB::commit();
            return $this->returnSuccessMessage('country updated successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function delete_country($request)
    {
        // TODO: Implement delete_country() method.
        try {
            DB::beginTransaction();
            $country_id=request()->country_id;
            $country=Country::where('id',$country_id)->first();
            if (!$country) {
                DB::rollBack();
                return $this->returnError(404,'country not found');
            }
            $country->delete();
            DB::commit();
            return $this->returnSuccessMessage('country deleted successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_all_cities()
    {
        // TODO: Implement get_all_cities() method.
        try {
            $cities=City::with('translation')->get();
            return $this->returnData('cities',IdNameResource::collection($cities));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function add_city($request)
    {
        // TODO: Implement add_city() method.
        try {
            DB::beginTransaction();
            $city_name=request()->city_name;
            $country_id=request()->country_id;
            City::create([
                'name'=>$city_name,
                'country_id'=>$country_id
            ]);
            DB::commit();
            return $this->returnSuccessMessage('city added successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function update_city($request)
    {
        // TODO: Implement update_city() method.
        try {
            DB::beginTransaction();
            $city_id=request()->city_id;
            $city=City::where('id',$city_id)->first();
            if (!$city) {
                DB::rollBack();
                return $this->returnError(404,'city not found');
            }
            if (request()->city_name!=null)
            {
                $city_name=request()->city_name;
                $city->name=$city_name;
            }
            if (request()->country_id!=null)
            {
                $country_id=request()->country_id;
                $city->country_id=$country_id;
            }
            $city->save();
            DB::commit();
            return $this->returnSuccessMessage('city updated successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function delete_city($request)
    {
        // TODO: Implement delete_city() method.
        try {
            DB::beginTransaction();
            $city_id=request()->city_id;
            $city=City::where('id',$city_id)->first();
            if (!$city) {
                DB::rollBack();
                return $this->returnError(404,'city not found');
            }
            $city->delete();
            DB::commit();
            return $this->returnSuccessMessage('city deleted successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_all_regions()
    {
        // TODO: Implement get_all_regions() method.
        try {
            $regions=Region::with('translation')->get();
            return $this->returnData('regions',IdNameResource::collection($regions));
        }catch (\Exception $e){
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function add_region($request)
    {
        // TODO: Implement add_region() method.
        try {
            DB::beginTransaction();
            $region_name=request()->region_name;
            $city_id=request()->city_id;
            Region::create([
                'name'=>$region_name,
                'city_id'=>$city_id
            ]);
            DB::commit();
            return $this->returnSuccessMessage('region added successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function update_region($request)
    {
        // TODO: Implement update_region() method.
        try {
            DB::beginTransaction();
            $region_id=request()->region_id;
            $region=Region::where('id',$region_id)->first();
            if (!$region) {
                DB::rollBack();
                return $this->returnError(404,'region not found');
            }
            if (request()->region_name!=null)
            {
                $region_name=request()->region_name;
                $region->name=$region_name;
            }
            if (request()->city_id!=null)
            {
                $city_id=request()->city_id;
                $region->city_id=$city_id;
            }
            $region->save();
            DB::commit();
            return $this->returnSuccessMessage('region updated successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function delete_region($request)
    {
        // TODO: Implement delete_region() method.
        try {
            DB::beginTransaction();
            $region_id=request()->region_id;
            $region=Region::where('id',$region_id)->first();
            if (!$region) {
                DB::rollBack();
                return $this->returnError(404,'region not found');
            }
            $region->delete();
            DB::commit();
            return $this->returnSuccessMessage('region deleted successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function get_all_address()
    {
        // TODO: Implement get_all_address() method.
        try {
            $countries=Country::with(['cities.regions','translation'])->get();
            return $this->returnData('countries',CountryResource::collection($countries));
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
}
