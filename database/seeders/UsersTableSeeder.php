<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        $date_at=Carbon::now()->format('Y-m-d H:i:s');
        $users=[
            [
                'id'             => 1,
                'first_name'           => 'Bela',
                'last_name'           => 'Waseet',
                'email'          => 'safwetbilal65@gmail.com',
                'password'       => '$2y$10$jOKSSzQ916X/ItuwIXfWKO3mLsnq5zJFAYaoRgW6Z.a2bqs7vTYN6',
                'image_url' => fake('ar_JO')->imageUrl(),
                'secondary_address'=>fake('ar_JO')->address,
                'phone_number'=>fake('ar_JO')->phoneNumber,
                'verify'=>true,
                'remember_token' => Str::random(10),
                'region_id'=>rand(1,Region::count()),
                'created_at'     => $date_at,
                'updated_at'     => $date_at,
            ],
            [
                'id'             => 2,
                'first_name'           => 'Waseem',
                'last_name'           => 'Saleh',
                'email'          => 'wasem.saleh328@gmail.com',
                'password'       => '$2y$10$jOKSSzQ916X/ItuwIXfWKO3mLsnq5zJFAYaoRgW6Z.a2bqs7vTYN6',
                'image_url' => fake('ar_JO')->imageUrl(),
                'secondary_address'=>fake('ar_JO')->address,
                'phone_number'=>fake('ar_JO')->phoneNumber,
                'verify'=>true,
                'remember_token' => Str::random(10),
                'region_id'=>rand(1,Region::count()),
                'created_at'     => $date_at,
                'updated_at'     => $date_at,
            ],
//            [
//                'id'             => 3,
//                'name'           => 'Waseem',
//                'email'          => 'waseem@waseem.com',
//                'password'       => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
//                'remember_token' => null,
//                'created_at'     => $date_at,
//                'updated_at'     => $date_at,
//            ],
        ];

        User::insert($users);

        User::factory()->count(1)->create();
    }
}
