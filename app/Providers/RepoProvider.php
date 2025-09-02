<?php

namespace App\Providers;

use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Dashboard\MerchantAddOrders\MerchantAddOrdersRepository;
use App\Repositories\Dashboard\MerchantAddOrders\MerchantAddOrdersRepositoryInterface;
use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\Employee\EmployeeRepositoryInterface;


use App\Repositories\Dashboard\Address\AddressManagementRepository;
use App\Repositories\Dashboard\Address\AddressManagementRepositoryInterface;
use App\Repositories\Dashboard\Admin\AdminRepository;
use App\Repositories\Dashboard\Admin\AdminRepositoryInterface;
use App\Repositories\Dashboard\AdvertisingPackage\AdvertisingPackageManagementRepository;
use App\Repositories\Dashboard\AdvertisingPackage\AdvertisingPackageManagementRepositoryInterface;
use App\Repositories\Dashboard\Auth\AuthManagementRepository;
use App\Repositories\Dashboard\Auth\AuthManagementRepositoryInterface;
use App\Repositories\Dashboard\Complaints\ComplaintManagementRepository;
use App\Repositories\Dashboard\Complaints\ComplaintManagementRepositoryInterface;
use App\Repositories\Dashboard\Config\ConfigDashboardDeveloperRepository;
use App\Repositories\Dashboard\Config\ConfigDashboardDeveloperRepositoryInterface;
use App\Repositories\Dashboard\Orders\OrdersRepository;
use App\Repositories\Dashboard\Orders\OrdersRepositoryInterface;
use App\Repositories\Dashboard\Properties\PropertiesManagementRepositoryInterface;
use App\Repositories\Dashboard\Properties\propertiesManagementRepository;
use App\Repositories\Dashboard\Services\ServicesManagementRepository;
use App\Repositories\Dashboard\Services\ServicesManagementRepositoryInterface;
use App\Repositories\Dashboard\Statistics\StatisticsRepository;
use App\Repositories\Dashboard\Statistics\StatisticsRepositoryInterface;
use App\Repositories\Dashboard\SuperAdmin\SuperAdminRepository;
use App\Repositories\Dashboard\SuperAdmin\SuperAdminRepositoryInterface;
use App\Repositories\Dashboard\User\AdminUserRepository;
use App\Repositories\Dashboard\User\AdminUserRepositoryInterface;
use App\Repositories\Mobile\Address\AddressUserRepository;
use App\Repositories\Mobile\Address\AddressUserRepositoryInterface;
use App\Repositories\Mobile\AdvertisingPackage\AdvertisingPackageUserRepository;
use App\Repositories\Mobile\AdvertisingPackage\AdvertisingPackageUserRepositoryInterface;
use App\Repositories\Mobile\Auth\AuthUserRepository;
use App\Repositories\Mobile\Auth\AuthUserRepositoryInterface;
use App\Repositories\Mobile\Complaints\ComplaintUserRepository;
use App\Repositories\Mobile\Complaints\ComplaintUserRepositoryInterface;
use App\Repositories\Mobile\Config\ConfigMobileDeveloperRepository;
use App\Repositories\Mobile\Config\ConfigMobileDeveloperRepositoryInterface;
use App\Repositories\Mobile\Properties\PropertiesUserRepositoryInterface;
use App\Repositories\Mobile\Properties\propertiesUserRepository;
use App\Repositories\Mobile\Services\ServicesUserRepository;
use App\Repositories\Mobile\Services\ServicesUserRepositoryInterface;
use App\Repositories\Mobile\User\UserRepository;
use App\Repositories\Mobile\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepoProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AddressManagementRepositoryInterface::class,AddressManagementRepository::class);
        $this->app->bind(AdminRepositoryInterface::class,AdminRepository::class);
        $this->app->bind(AdvertisingPackageManagementRepositoryInterface::class,AdvertisingPackageManagementRepository::class);
        $this->app->bind(AuthManagementRepositoryInterface::class,AuthManagementRepository::class);
        $this->app->bind(ComplaintManagementRepositoryInterface::class,ComplaintManagementRepository::class);
        $this->app->bind(ConfigDashboardDeveloperRepositoryInterface::class,ConfigDashboardDeveloperRepository::class);
        $this->app->bind(OrdersRepositoryInterface::class,OrdersRepository::class);
        $this->app->bind(PropertiesManagementRepositoryInterface::class,propertiesManagementRepository::class);
        $this->app->bind(ServicesManagementRepositoryInterface::class,ServicesManagementRepository::class);
        $this->app->bind(StatisticsRepositoryInterface::class,StatisticsRepository::class);
        $this->app->bind(SuperAdminRepositoryInterface::class,SuperAdminRepository::class);
        $this->app->bind(AdminUserRepositoryInterface::class,AdminUserRepository::class);
        $this->app->bind(AddressUserRepositoryInterface::class,AddressUserRepository::class);
        $this->app->bind(AdvertisingPackageUserRepositoryInterface::class,AdvertisingPackageUserRepository::class);
        $this->app->bind(AuthUserRepositoryInterface::class,AuthUserRepository::class);
        $this->app->bind(ComplaintUserRepositoryInterface::class,ComplaintUserRepository::class);
        $this->app->bind(ConfigMobileDeveloperRepositoryInterface::class,ConfigMobileDeveloperRepository::class);
        $this->app->bind(PropertiesUserRepositoryInterface::class,propertiesUserRepository::class);
        $this->app->bind(ServicesUserRepositoryInterface::class,ServicesUserRepository::class);
        $this->app->bind(UserRepositoryInterface::class,UserRepository::class);
        $this->app->bind(MerchantAddOrdersRepositoryInterface::class,MerchantAddOrdersRepository::class);



    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
