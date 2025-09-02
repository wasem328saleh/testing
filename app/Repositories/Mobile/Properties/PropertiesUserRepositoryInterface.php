<?php

namespace App\Repositories\Mobile\Properties;



interface PropertiesUserRepositoryInterface
{

    public function add_property($request);
    public function get_my_properties();
    public function edited_my_property($request);
    public function deleted_my_property($request);
    public function edited_activation_my_property($request);
    public function get_all_properties($city_id);
    public function get_all_properties_sale($city_id);
    public function get_all_properties_rent($city_id);
    public function search_filter_properties($request);
    public function search_properties_by_cityId($request);
    public function search_properties_by_serialNumber($request);
    public function get_all_directions();
    public function get_all_rooms_types();
    public function get_all_main_categories();
    public function get_all_sub_categories_by_id_main_category($id);
    public function get_all_categories();

    public function add_property_photos($request);
    public function delete_property_photos($request);
    public function get_all_ownership_types();
    public function get_all_pledge_types();

}
