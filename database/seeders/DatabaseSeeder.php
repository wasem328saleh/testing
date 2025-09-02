<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Events\DeleteAllEvent;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//$this->call(ProjectSeed::class);
        event(new DeleteAllEvent());
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            AddressSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
//            PermissionUserTableSeeder::class,
            ProjectSeed::class,
            DeviceTokenTableSeeder::class,
        ]);

    }
}
