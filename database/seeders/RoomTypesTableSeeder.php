<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('room_types')->delete();
        DB::table('room_types')->delete();
        $roomType= [
            'صالون',
            'معيشة',
            'غرفة نوم',
            'مطبخ',
            'حمام',
        ];
        foreach ($roomType as $type) {
            RoomType::create([
                'name' => $type,
            ]);
        }
    }
}
