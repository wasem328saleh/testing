<?php

namespace App\Repositories\Dashboard\AdvertisingPackage;

interface AdvertisingPackageManagementRepositoryInterface
{
    public function get_all_advertising_packages();

    public function add_advertising_package($request);

    public function update_advertising_package($request);

    public function delete_advertising_package($request);

    public function get_all_advertising($request);
    public function getAdvertisementById($id); //advertisement_id

    public function add_advertising($request);

    public function update_advertising($request);

    public function delete_advertising($request);

    public function change_status_advertising($request);

    public function get_all_archived_advertising();

    public function remove_from_archived($request);

    public function unarchived_from_archived($request);

    public function get_all_features($request);

    public function add_feature($request);

    public function update_feature($request);

    public function delete_feature($request);

    public function get_all_classifications();
    public function add_classification($request);
    public function update_classification($request);
    public function delete_classification($request);
    public function change_status_classification($request);

}
