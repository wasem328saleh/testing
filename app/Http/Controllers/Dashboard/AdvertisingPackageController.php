<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Advertising\PackageRequest;
use App\Repositories\Dashboard\AdvertisingPackage\AdvertisingPackageManagementRepositoryInterface;
use Illuminate\Http\Request;

class AdvertisingPackageController extends Controller
{
    protected AdvertisingPackageManagementRepositoryInterface $advertisingPackage;

    /**
     * @param AdvertisingPackageManagementRepositoryInterface $advertisingPackage
     */
    public function __construct(AdvertisingPackageManagementRepositoryInterface $advertisingPackage)
    {
        $this->advertisingPackage = $advertisingPackage;
    }

    public function get_all_advertising(PackageRequest $request){
        return $this->advertisingPackage->get_all_advertising($request);
    }
        public function getAdvertisementById($id){
        return $this->advertisingPackage->getAdvertisementById($id);
    }

    public function get_all_advertising_packages()
    {
        return $this->advertisingPackage->get_all_advertising_packages();
    }

    public function add_advertising_package(PackageRequest $request){
        return $this->advertisingPackage->add_advertising_package($request);
    }

    public function update_advertising_package(PackageRequest $request){
        return $this->advertisingPackage->update_advertising_package($request);
    }

    public function delete_advertising_package(PackageRequest $request){
        return $this->advertisingPackage->delete_advertising_package($request);
    }

    public function add_advertising(PackageRequest $request){
        return $this->advertisingPackage->add_advertising($request);
    }

    public function update_advertising(PackageRequest $request){
        return $this->advertisingPackage->update_advertising($request);
    }

    public function delete_advertising(PackageRequest $request){
        return $this->advertisingPackage->delete_advertising($request);
    }

    public function change_status_advertising(PackageRequest $request){
        return $this->advertisingPackage->change_status_advertising($request);
    }

    public function get_all_archived_advertising(){
        return $this->advertisingPackage->get_all_archived_advertising();
    }

    public function remove_from_archived(PackageRequest $request){
        return $this->advertisingPackage->remove_from_archived($request);
    }

    public function unarchived_from_archived(PackageRequest $request){
        return $this->advertisingPackage->unarchived_from_archived($request);
    }

    public function get_all_features(PackageRequest $request){
        return $this->advertisingPackage->get_all_features($request);
    }

    public function add_feature(PackageRequest $request){
        return $this->advertisingPackage->add_feature($request);
    }

    public function update_feature(PackageRequest $request){
        return $this->advertisingPackage->update_feature($request);
    }

    public function delete_feature(PackageRequest $request){
        return $this->advertisingPackage->delete_feature($request);
    }

    public function get_all_classifications(){
        return $this->advertisingPackage->get_all_classifications();
    }
    public function add_classification(PackageRequest $request){
        return $this->advertisingPackage->add_classification($request);
    }
    public function update_classification(PackageRequest $request){
        return $this->advertisingPackage->update_classification($request);
    }
    public function delete_classification(PackageRequest $request){
        return $this->advertisingPackage->delete_classification($request);
    }
    public function change_status_classification(PackageRequest $request){
        return $this->advertisingPackage->change_status_classification($request);
    }
}
