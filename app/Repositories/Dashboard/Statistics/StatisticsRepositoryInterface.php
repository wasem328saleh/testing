<?php

namespace App\Repositories\Dashboard\Statistics;

interface StatisticsRepositoryInterface
{
    public function get_all_statistics_users();
    public function get_all_statistics_advertising_packages();
    public function get_statistics_advertising_package($request);
    public function get_statistics_attributes($request);

    public function get_statistics_rating($request);
    public function get_statistics_financial($request);
    public function get_statistics_services();
    public function get_statistics_services_by_id($request);
    public function get_statistics_providers_services();
    public function get_statistics_estate();
    public function get_statistics_estate_category($request);
}
