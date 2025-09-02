<?php

namespace App\Repositories\Dashboard\Properties;

interface PropertiesManagementRepositoryInterface
{
    public function get_all_properties();//
    public function get_all_orders();
    public function add_property($request);//
    public function update_property($request);//
    public function delete_property($request);//
    public function edited_activation_property($request);//
    public function change_property_status($action,$request);//
    public function get_all_archived_properties();
    public function remove_from_archived($request);
    public function unarchived_from_archived($request);
    public function get_all_rooms_types();
    public function add_room_type($request);
    public function update_room_type($request);
    public function delete_room_type($request);
    public function get_all_main_categories();
    public function add_main_category($request);
    public function update_main_category($request);
    public function delete_main_category($request);
    public function get_all_sub_categories_by_id_main_category($id);
    public function add_sub_category($request);
    public function update_sub_category($request);
    public function delete_sub_category($request);
    public function get_all_categories();
    public function get_all_directions();
    public function add_direction($request);
    public function update_direction($request);
    public function delete_direction($request);

    public function get_all_ownership_types();
    public function add_ownership_type($request);
    public function update_ownership_type($request);
    public function delete_ownership_type($request);

    public function get_all_pledge_types();
    public function add_pledge_type($request);
    public function update_pledge_type($request);
    public function delete_pledge_type($request);
}
