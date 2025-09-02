<?php

namespace App\Repositories\Mobile\AdvertisingPackage;

interface AdvertisingPackageUserRepositoryInterface
{
    public function get_all_packages($request);
    public function subscribe_in_package($request);
    public function add_advertising($request);
    public function get_my_advertising($request);
    public function edited_my_advertising($request);
    public function deleted_my_advertising($request);
    public function edited_activation_advertising($request);
    public function get_all_advertising($request);
    public function get_all_advertising_sale($request);
    public function get_all_advertising_rent($request);
    public function search_filter_advertising($request);
    public function search_advertising_by_cityId($request);
    public function search_advertising_by_serialNumber($request);
    public function get_my_subscriber($request);
    public function resubscribe_advertising($request);
    public function rate_advertising($request);
    public function add_medias($request);
    public function delete_media($request);

}
