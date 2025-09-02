<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestRequest;
use App\Repositories\Mobile\Properties\PropertiesUserRepositoryInterface;
use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    protected PropertiesUserRepositoryInterface $properties;

    /**
     * @param PropertiesUserRepositoryInterface $properties
     */
    public function __construct(PropertiesUserRepositoryInterface $properties)
    {
        $this->properties = $properties;
    }

    public function get_all_directions()
    {
        return $this->properties->get_all_directions();
    }
    public function get_all_rooms_types()
    {
        return $this->properties->get_all_rooms_types();
    }
    public function get_all_main_categories()
    {
        return $this->properties->get_all_main_categories();
    }
    public function get_all_sub_categories_by_id_main_category($id)
    {
        return $this->properties->get_all_sub_categories_by_id_main_category($id);
    }
    public function get_all_categories()
    {
        return $this->properties->get_all_categories();
    }
    public function get_all_ownership_types(){
        return $this->properties->get_all_ownership_types();
    }

    public function get_all_pledge_types()
    {
        return $this->properties->get_all_pledge_types();
    }
}
