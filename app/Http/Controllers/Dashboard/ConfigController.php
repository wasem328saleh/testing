<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Config\ConfigRequest;
use App\Repositories\Dashboard\Config\ConfigDashboardDeveloperRepositoryInterface;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    protected ConfigDashboardDeveloperRepositoryInterface $config;

    /**
     * @param ConfigDashboardDeveloperRepositoryInterface $config
     */
    public function __construct(ConfigDashboardDeveloperRepositoryInterface $config)
    {
        $this->config = $config;
    }

    public function get_classifications(){
        return $this->config->get_classifications();
    }
    public function get_config_attributes(ConfigRequest $request){
        return $this->config->get_config_attributes($request);
    }
    public function get_features(ConfigRequest $request){
        return $this->config->get_features($request);
    }
    public function get_languages(){
        return $this->config->get_languages();
    }
    public function get_language_app(){
        return $this->config->get_language_app();
    }
    public function change_language(ConfigRequest $request){
        return $this->config->change_language($request);
    }
    public function get_all_config(){
        return $this->config->get_all_config();
    }


}
