<?php

namespace App\Repositories\Dashboard\Config;

interface ConfigDashboardDeveloperRepositoryInterface
{
    public function get_classifications();
    public function get_config_attributes($request);
    public function get_features($request);
    public function get_languages();
    public function get_language_app();
    public function change_language($request);
    public function get_all_config();
}
