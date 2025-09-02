<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Properties\PropertiesRequest;
use App\Repositories\Dashboard\Properties\PropertiesManagementRepositoryInterface;
use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    protected PropertiesManagementRepositoryInterface $properties;

    /**
     * @param PropertiesManagementRepositoryInterface $properties
     */
    public function __construct(PropertiesManagementRepositoryInterface $properties)
    {
        $this->properties = $properties;
    }



    public function get_all_rooms_types(){
        return $this->properties->get_all_rooms_types();
    }
    public function add_room_type(PropertiesRequest $request){
        return $this->properties->add_room_type($request);
    }
    public function update_room_type(PropertiesRequest $request){
        return $this->properties->update_room_type($request);
    }
    public function delete_room_type(PropertiesRequest $request){
        return $this->properties->delete_room_type($request);
    }



    public function get_all_main_categories(){
        return $this->properties->get_all_main_categories();
    }
    public function add_main_category(PropertiesRequest $request){
        return $this->properties->add_main_category($request);
    }
    public function update_main_category(PropertiesRequest $request){
        return $this->properties->update_main_category($request);
    }
    public function delete_main_category(PropertiesRequest $request){
        return $this->properties->delete_main_category($request);
    }



    public function get_all_sub_categories_by_id_main_category($id){
        return $this->properties->get_all_sub_categories_by_id_main_category($id);
    }
    public function add_sub_category(PropertiesRequest $request){
        return $this->properties->add_sub_category($request);
    }
    public function update_sub_category(PropertiesRequest $request){
        return $this->properties->update_sub_category($request);
    }
    public function delete_sub_category(PropertiesRequest $request){
        return $this->properties->delete_sub_category($request);
    }



    public function get_all_categories(){
        return $this->properties->get_all_categories();
    }


    public function get_all_directions(){
        return $this->properties->get_all_directions();
    }
    public function add_direction(PropertiesRequest $request){
        return $this->properties->add_direction($request);
    }
    public function update_direction(PropertiesRequest $request){
        return $this->properties->update_direction($request);
    }
    public function delete_direction(PropertiesRequest $request){
        return $this->properties->delete_direction($request);
    }

    public function get_all_ownership_types()
    {
        return $this->properties->get_all_ownership_types();
    }
    public function add_ownership_type(PropertiesRequest $request)
    {
        return $this->properties->add_ownership_type($request);
    }
    public function update_ownership_type(PropertiesRequest $request)
    {
        return $this->properties->update_ownership_type($request);
    }
    public function delete_ownership_type(PropertiesRequest $request)
    {
        return $this->properties->delete_ownership_type($request);
    }

    public function get_all_pledge_types()
    {
        return $this->properties->get_all_pledge_types();
    }
    public function add_pledge_type(PropertiesRequest $request)
    {
        return $this->properties->add_pledge_type($request);
    }
    public function update_pledge_type(PropertiesRequest $request)
    {
        return $this->properties->update_pledge_type($request);
    }
    public function delete_pledge_type(PropertiesRequest $request)
    {
        return $this->properties->delete_pledge_type($request);
    }

}
