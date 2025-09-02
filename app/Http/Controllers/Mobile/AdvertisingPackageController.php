<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Advertising\PackageRequest;
use App\Http\Requests\TestRequest;
use App\Repositories\Mobile\AdvertisingPackage\AdvertisingPackageUserRepositoryInterface;
use Illuminate\Http\Request;

class AdvertisingPackageController extends Controller
{
    protected AdvertisingPackageUserRepositoryInterface $advertisingPackage;

    /**
     * @param AdvertisingPackageUserRepositoryInterface $advertisingPackage
     */
    public function __construct(AdvertisingPackageUserRepositoryInterface $advertisingPackage)
    {
        $this->advertisingPackage = $advertisingPackage;
    }

    public function get_all_packages(PackageRequest $request){
        return $this->advertisingPackage->get_all_packages($request);
    }
    public function subscribe_in_package(PackageRequest $request){
        return $this->advertisingPackage->subscribe_in_package($request);
    }
    public function add_advertising(PackageRequest $request){
        return $this->advertisingPackage->add_advertising($request);
    }
    public function get_my_advertising(PackageRequest $request){
        return $this->advertisingPackage->get_my_advertising($request);
    }
    public function edited_my_advertising(PackageRequest $request){
        return $this->advertisingPackage->edited_my_advertising($request);
    }
    public function deleted_my_advertising(PackageRequest $request){
        return $this->advertisingPackage->deleted_my_advertising($request);
    }
    public function edited_activation_advertising(PackageRequest $request){
        return $this->advertisingPackage->edited_activation_advertising($request);
    }
    public function get_all_advertising(PackageRequest $request){
        return $this->advertisingPackage->get_all_advertising($request);
    }

    public function get_all_advertising_sale(PackageRequest $request){
        return $this->advertisingPackage->get_all_advertising_sale($request);
    }

    public function get_all_advertising_rent(PackageRequest $request){
        return $this->advertisingPackage->get_all_advertising_rent($request);
    }

    public function search_filter_advertising(PackageRequest $request){
        return $this->advertisingPackage->search_filter_advertising($request);
    }
    public function search_advertising_by_cityId(PackageRequest $request){
        return $this->advertisingPackage->search_advertising_by_cityId($request);
    }
    public function resubscribe_advertising(PackageRequest $request){
        return $this->advertisingPackage->resubscribe_advertising($request);
    }
    public function get_my_subscriber(PackageRequest $request){
        return $this->advertisingPackage->get_my_subscriber($request);
    }
    public function rate_advertising(PackageRequest $request){
        return $this->advertisingPackage->rate_advertising($request);
    }

    public function add_medias(PackageRequest $request){
        return $this->advertisingPackage->add_medias($request);
    }
    public function delete_media(PackageRequest $request){
        return $this->advertisingPackage->delete_media($request);
    }

    public function search_advertising_by_serialNumber(PackageRequest $request){
        return $this->advertisingPackage->search_advertising_by_serialNumber($request);
    }

}
