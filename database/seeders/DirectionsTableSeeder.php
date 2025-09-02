<?php

namespace Database\Seeders;

use App\Models\Direction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('directions')->delete();
       $directions = [
           'شرقي',
           'غربي',
           'شمالي',
           'جنوبي',];
       foreach ($directions as $direction) {
           Direction::create(['title'=>$direction]);
       }
    }
}
