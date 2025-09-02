<?php

namespace Database\Seeders;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->delete();
        $date_at=Carbon::now()->format('Y-m-d H:i:s');
        $permissions = [
            [
                'id'         => '1',
                'title'      => 'user_management_access',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '2',
                'title'      => 'permission_create',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '3',
                'title'      => 'permission_edit',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '4',
                'title'      => 'permission_show',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '5',
                'title'      => 'permission_delete',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '6',
                'title'      => 'permission_access',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '7',
                'title'      => 'role_create',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '8',
                'title'      => 'role_edit',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '9',
                'title'      => 'role_show',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '10',
                'title'      => 'role_delete',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '11',
                'title'      => 'role_access',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '12',
                'title'      => 'user_create',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '13',
                'title'      => 'user_edit',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '14',
                'title'      => 'user_show',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '15',
                'title'      => 'user_delete',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '16',
                'title'      => 'user_access',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '17',
                'title'      => 'test_permission1',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '18',
                'title'      => 'user_test_tow_gate',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '19',
                'title'      => 'permission_user',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '20',
                'title'      => 'test_permission2',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '21',
                'title'      => 'test_permission3',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '22',
                'title'      => 'merchant_permission1',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '23',
                'title'      => 'merchant_permission2',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => '24',
                'title'      => 'merchant_permission3',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],

        ];

        Permission::insert($permissions);

    }
}
