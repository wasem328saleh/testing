<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return !str_starts_with($permission->title, 'user_')
                && !str_starts_with($permission->title, 'role_')
                && !str_starts_with($permission->title, 'permission_')
                && !str_starts_with($permission->title, 'merchant_');
        });
        $merchant_permissions=$admin_permissions->filter(function ($permission) {
            return str_starts_with($permission->title, 'merchant_');
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions);
        Role::findOrFail(3)->permissions()->sync($merchant_permissions);
    }
}
