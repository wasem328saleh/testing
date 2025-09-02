<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->delete();
        $date_at=Carbon::now()->format('Y-m-d H:i:s');
        $roles = [
            [
                'id'         => 1,
                'title'      => 'super_admin',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => 2,
                'title'      => 'user',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => 3,
                'title'      => 'merchant',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
            [
                'id'         => 4,
                'title'      => 'admin',
                'created_at' => $date_at,
                'updated_at' => $date_at,
            ],
        ];

        Role::insert($roles);
    }
}
